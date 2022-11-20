<?php

// Роутинг, основная функция
function route($data) {

    // GET /photo
    if ($data['method'] === 'GET' && count($data['urlData']) === 1) {
        echo json_encode(getPhotos($_SESSION['user']['login']));
        exit;
    }
    // GET /photo/mary
    if ($data['method'] === 'GET' && count($data['urlData']) === 2) {
        echo json_encode(getPhotos($data['urlData'][1]));
        exit;
    }

    // POST /photo
    if ($data['method'] === 'POST') {
        echo json_encode(addPhoto($data['formData']));
        exit;
    }

    // PUT /photo/5
    if ($data['method'] === 'PUT' && count($data['urlData']) === 2 && isset($data['formData']['title'])) {
        $id = (int)$data['urlData'][1];
        $title = $data['formData']['title'];

        echo json_encode(updatePhoto($id, $title));
        exit;
    }

    // DELETE /photo/5
    if ($data['method'] === 'DELETE' && count($data['urlData']) === 2) {
        $id = (int)$data['urlData'][1];

        echo json_encode(deletePhoto($id));
        exit;
    }


    // Если ни один роутер не отработал
    \Helpers\query\throwHttpError('invalid_parameters', 'invalid parameters');

}

// Возвращаем все фото
function getPhotos($user_login) {
    try{
        $pdo = \Helpers\query\connectDB();
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('database error connect', $e->getMessage());
        exit;
    }

    if(!\Helpers\query\isExistsUserByLogin($pdo, $user_login)) {
        \Helpers\query\throwHttpError('user not exists: ', 'пользователь с таким id не существует');
        exit;
    }

    try {
        $query = 'SELECT user_id FROM "user" WHERE user_login=?';
        $data = $pdo->prepare($query);
        $data->execute([$user_login]);

        $row = $data->fetch(PDO::FETCH_LAZY);
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('query error', $e->getMessage());
        exit;
    }

    try {
        $query = 'SELECT * FROM photo WHERE user_id=?';
        $data = $pdo->prepare($query);
        $data->execute([$row->user_id]);
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('query error', $e->getMessage());
        exit;
    }

    $result = [
        'meta' => [],
        'records' => []
    ];

    while($row = $data->fetch(PDO::FETCH_LAZY)) {
        if(!$row->photo_id) continue;

        $photoids[] = $row->photo_id;
        
        $result['records'][$row->photo_id] = [
            'path' => $row->photo_path,
            'desc' => $row->photo_description,
            'id' => $row->photo_id,
            'date' => $row->photo_date
        ];
    }

    if(count($result['records']) == 0){
        \Helpers\query\throwHttpError('photo not found', 'пользователь не загрузил фото', '404 photo not found');
        exit;
    }

    try {
        $in = str_repeat('?,', count($photoids) - 1) . '?';
        $query = "SELECT * FROM rating WHERE photo_id IN ($in)";
        $data = $pdo->prepare($query);
        $data->execute($photoids);
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('query error', $e->getMessage());
        exit;
    }

    while($row = $data->fetch(PDO::FETCH_LAZY)) {
        if(!$rating[$row->photo_id]){
            $rating[$row->photo_id] = [
                'value' => 0,
                'count' => 0
            ];
        }

        $rating[$row->photo_id]['value'] += $row->rating_value;
        ++$rating[$row->photo_id]['count'];
    }


    foreach($result['records'] as $key => $item){
        if($rating[$key]['count'] > 0)
            $r = round($rating[$key]['value'] / $rating[$key]['count'], 1);
        else 
            $r = 0;

        $result['records'][$key]['rating'] = $r;
    }


    return $result;
}


// Добавление фото для текущего юзера
function addPhoto($fdata) {
    try{
        $pdo = \Helpers\query\connectDB();
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('database error connect', $e->getMessage());
        exit;
    }

    try {
        $file = \Helpers\files\loadfile('add-post-file')[0];

        $query = 'INSERT INTO photo (photo_description, photo_path, user_id) 
                    VALUES (:description, :path, :user_id)';
                    
        $data = $pdo->prepare($query);

        $data->execute([
            'description' => $fdata['desc'],
            'path' => $file,
            'user_id' => $_SESSION['user_id']
        ]);
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('query error', $e->getMessage(), '400 error post add');
        exit;
    } catch (\Exception $e) {
        \Helpers\query\throwHttpError('file load error', $e->getMessage(), '400 file load error');
        exit;
    }

    ++$_SESSION['user']['post_count'];

    // Новый айдишник для добавленного поста
    $newId = (int)$pdo->lastInsertId();

    return array(
        'id' => $newId,
        'desc' => $fdata['desc']
    );
}


// Удаление фото
function deletePhoto($id) {
    try{
        $pdo = \Helpers\query\connectDB();
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('database error connect', $e->getMessage());
        exit;
    }

    // Если бренд не существует, то выбрасываем ошибку
    if (!\Helpers\query\isExistsPostById($pdo, $id)) {
        \Helpers\query\throwHttpError('photo not exists', 'Фото не существует. Возможно, вы удалили его ранее.', '404 photo not exists');
        exit;
    }

    // Удаляем фото из базы
    try {
        $query = 'DELETE FROM photo WHERE photo_id=:id AND user_id=:user_id';

        $data = $pdo->prepare($query);        
        $data->execute([
            'id' => $id,
            'user_id' => $_SESSION['user_id']
        ]);
    } catch (PDOException $e) {
        echo $e->getMessage();
        \Helpers\query\throwHttpError('query error', $e->getMessage(), '400 error post delete');
        exit;
    }

    --$_SESSION['user']['post_count'];

    return array(
        'id' => $id
    );
}
