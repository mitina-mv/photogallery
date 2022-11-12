<?php

// Роутинг, основная функция
function route($data) {
    // GET /user/
    if ($data['method'] === 'GET' && count($data['urlData']) === 1) {
        $data['formData']["v"] = htmlspecialchars($data['formData']["v"]);
        echo json_encode(getUser($data['formData']["v"]));
        exit;
    }

    // GET /user/mary2001
    if ($data['method'] === 'GET' && count($data['urlData']) === 2) {
        $login = htmlspecialchars($data['urlData'][1]);
        
        echo json_encode(getUserDetail($login));
        exit;
    }

    // Если ни один роутер не отработал
    \Helpers\query\throwHttpError('invalid_parameters', 'invalid parameters');
}

function getUser($searchText){
    try{
        $pdo = \Helpers\query\connectDB();
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('database error connect', $e->getMessage());
        exit;
    }

    try {
        $query = 'SELECT * FROM "user" WHERE user_login LIKE ? OR user_firstname LIKE ? OR        user_lastname LIKE ?';
        $params = ["%$searchText%", "%$searchText%", "%$searchText%"];
        $data = $pdo->prepare($query);
        $data->execute($params);
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('query error', $e->getMessage());
        exit;
    }

    $result = [];

    while($row = $data->fetch(PDO::FETCH_LAZY)) {
        $result[] = [
            'firstname' => $row->user_firstname,
            'lastname' => $row->user_lastname,
            'login' => $row->user_login,
            'photo' => $row->user_photo ?: null,
        ];
    }

    return $result;
}

function getUserDetail($login){
    try{
        $pdo = \Helpers\query\connectDB();
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('database error connect', $e->getMessage());
        exit;
    }

    if(!\Helpers\query\isExistsUserByLogin($pdo, $login)) {
        \Helpers\query\throwHttpError('user not exists: ', 'пост с таким id не существует');
        exit;
    }

    try{
        $query = 'select * from "user" where user_login=?';
        $data = $pdo->prepare($query);
        $data->execute([$login]);

        $row = $data->fetch(PDO::FETCH_LAZY);
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('query error', $e->getMessage());
        exit;
    }

    $result['user'] = [
        'firstname' => $row->user_firstname,
        'lastname' => $row->user_lastname,
        'login' => $row->user_login,
        'photo' => $row->user_photo ?: null
    ];

    return $result;
}