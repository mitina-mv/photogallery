<?php

// Роутинг, основная функция
function route($data) {
    // GET /detailpost/4
    if ($data['method'] === 'GET' && count($data['urlData']) === 2) {
        echo json_encode(getPost($data['urlData'][1]));
        exit;
    }

    // Если ни один роутер не отработал
    \Helpers\query\throwHttpError('invalid_parameters', 'invalid parameters');
}

function getPost($postID){
    // запрос фото
    try{
        $pdo = \Helpers\query\connectDB();
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('database error connect', $e->getMessage());
        exit;
    }

    if(!\Helpers\query\isExistsPostById($pdo, $postID)) {
        \Helpers\query\throwHttpError('post not exists: ', 'пост с таким id не существует');
        exit;
    }

    try {
        $query = 'SELECT * FROM photo WHERE photo_id=?';
        $data = $pdo->prepare($query);
        $data->execute([$postID]);
        
        $row = $data->fetch(PDO::FETCH_LAZY);
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('query error', $e->getMessage());
        exit;
    }

    $result = [
        'post' => [],
        'user' => [],
        'rating' => [
            'value' => 0,
            'count' => 0
        ]
    ];

    $result['post'] = [
        'path' => $row->photo_path,
        'desc' => $row->photo_description,
        'id' => $row->photo_id,
        'date' => $row->photo_date,
        'author' => $row->user_id,
    ];

    // запрос текущей оценки фото
    try {
        $query = 'SELECT rating_value FROM rating WHERE photo_id=?';
        $data = $pdo->prepare($query);
        $data->execute([$postID]);
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('query error', $e->getMessage());
        exit;
    }

    while($row = $data->fetch(PDO::FETCH_LAZY)) {
        $result['rating']['value'] += $row->rating_value;
        ++$result['rating']['count'];
    }

    if($result['rating']['count'] > 0)
        $result['post']['rating'] = round($result['rating']['value'] / $result['rating']['count'], 1);
    else 
        $result['post']['rating'] = 0;
        
    // запрос данные о авторе
    if($_SESSION['user_id'] == $result['post']['author']){
        $result['user'] = $_SESSION['user'];
    } else {
        try {
            $query = 'SELECT * FROM user WHERE user_id=?';
            $data = $pdo->prepare($query);
            $data->execute([$result['post']['author']]);
            
            $row = $data->fetch(PDO::FETCH_LAZY);
        } catch (PDOException $e) {
            \Helpers\query\throwHttpError('query error', $e->getMessage());
            exit;
        }

        $result['user'] = [
            'firstname' => $row->user_firstname,
            'lastname' => $row->user_lastname,
            'login' => $row->user_login,
            'photo' => $row->user_photo ?: null,
        ];
    }

    return $result;
}
