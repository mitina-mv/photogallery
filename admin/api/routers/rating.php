<?php

// Роутинг, основная функция
function route($data) {
    // POST /rating
    if ($data['method'] === 'POST' && count($data['urlData']) === 1) {
        echo json_encode(setRating($data['formData']));
        exit;
    }
}

function setRating($fData) {
    // проверка пользователя на манипуляцию с данными в куках
    if(md5($_SESSION['user_id']) != $_COOKIE['user-token']){
        \Helpers\query\throwHttpError('incorrect user', 'ошибка доступа, нет прав на установку оценки', '403 incorrect user');
        exit;
    }
    
    try{
        $pdo = \Helpers\query\connectDB();
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('database error connect', $e->getMessage());
        exit;
    }

    if(!\Helpers\query\isExistsPostById($pdo, $fData['id'])) {
        \Helpers\query\throwHttpError('post not exists: ', 'пост с таким id не существует', "404 post not exists");
        exit;
    }

    // проверка на существование оценки от текущего пользователя 
    if(\Helpers\query\isExistsRatingByPostAndUser($pdo, $fData['id'], $_SESSION['user_id'])) {
        \Helpers\query\throwHttpError('post has rating: ', 'нельзя изменить оценку.');
        exit;
    }

    // получение поста для проверки владения пользователя постом
    try {
        $query = 'SELECT user_id FROM photo WHERE photo_id=?';
        $data = $pdo->prepare($query);
        $data->execute([$fData['id']]);
        
        $row = $data->fetch(PDO::FETCH_LAZY);
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('query error', $e->getMessage());
        exit;
    }

    if($row->user_id == $_SESSION['user_id']) {
        \Helpers\query\throwHttpError('user owner', 'нет права оценивать собственный пост', '403 user owner');
        exit;
    }

    // добавление оценки
    try {
        $query = 'INSERT INTO rating (rating_value, photo_id, user_id) 
                    VALUES (:rating, :photo_id, :user_id)';
                    
        $data = $pdo->prepare($query);
        $data->execute([
            'rating' => $fData['rating'],
            'photo_id' => $fData['id'],
            'user_id' => $_SESSION['user_id']
        ]);
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('query error', $e->getMessage());
        exit;
    }

    $newId = (int)$pdo->lastInsertId();

    return array(
        'id' => $newId,
        'value' => $fData['rating']
    );
        
}
