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

    // POST /user
    if ($data['method'] === 'POST' && count($data['urlData']) === 1) {      
        echo json_encode(updateUser($data['formData']));
        exit;
    }

    // POST /user/reg
    if ($data['method'] === 'POST' && count($data['urlData']) === 2 && $data['urlData'][1] == 'reg') {      
        echo json_encode(addUser($data['formData']));
        exit;
    }

    // POST /user/auth
    if ($data['method'] === 'POST' && count($data['urlData']) === 2 && $data['urlData'][1] == 'auth') {      
        echo json_encode(authUser($data['formData']));
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
        $query = 'SELECT * FROM "user" WHERE (user_login LIKE ? OR user_firstname LIKE ? OR        user_lastname LIKE ?) AND user_id <> ?';
        $params = ["%$searchText%", "%$searchText%", "%$searchText%", $_SESSION['user_id']];
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
        \Helpers\query\throwHttpError('user not exists: ', 'пользователь не найден');
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

function addUser($fData) {
    $password = filter_var(trim($fData['password']), FILTER_SANITIZE_EMAIL);
    $passwordRepeat = filter_var(trim($fData['password-repeat']), FILTER_SANITIZE_EMAIL);

    $user = [
        'login' => filter_var(trim($fData['login']), FILTER_SANITIZE_STRING),
        'firstname' => filter_var(trim($fData['firstname']), FILTER_SANITIZE_STRING),
        'lastname' => filter_var(trim($fData['lastname']), FILTER_SANITIZE_STRING),
        'photo' => null,
        'bgimage' => null,
        'post_count' => 0,
        'rating' => 0
    ];

    if(mb_strlen($password) < 6 
        || preg_match("/[a-zA-Z0-9\-]*/", $password) !== 1 
        || $password != $passwordRepeat 
    ) {
        \Helpers\query\throwHttpError('password not suitable', 'пароль не подходит', '400 bad password');
        exit;
    }

    try{
        $pdo = \Helpers\query\connectDB();
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('database error connect', $e->getMessage());
        exit;
    }

    if(\Helpers\query\isExistsUserByLogin($pdo, $user['login'])) {
        \Helpers\query\throwHttpError('user exists: ', 'пользователь с таким логином уже существует', '400 user exists');
        exit();
    }

    try {
        $query = 'INSERT INTO "user" (user_login, user_firstname, user_lastname, user_password) 
        VALUES (:login, :firstname, :lastname, :password)';
        
        $data = $pdo->prepare($query);
        $data->execute([
            'login' => $user['login'],
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname'],
            'password' => md5(md5($password). "-pwd") 
            ]);
    } catch(PDOException $e) {
        \Helpers\query\throwHttpError('query error', $e->getMessage(), '400 query error');
        exit;
    }

    if($newId = (int)$pdo->lastInsertId()){
        $_SESSION['user_id'] = $newId;
        setcookie('user-token', md5($newId), 0, '/');

        $_SESSION['user'] = $user;
        return [
            'id' => $newId
        ];
    }

    return [];
}

function authUser($fData) {
    $login = filter_var(trim($fData['login']), FILTER_SANITIZE_STRING);
    $password = filter_var(trim($fData['password']), FILTER_SANITIZE_EMAIL);

    $password = md5(md5($password). "-pwd");

    try{
        $pdo = \Helpers\query\connectDB();
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('database error connect', $e->getMessage());
        exit;
    }

    try {
        $query = 'SELECT * FROM "user" WHERE user_password = :password AND user_login = :login';
        
        $data = $pdo->prepare($query);
        $data->execute([
            'password' => $password,
            'login' => $login, 
        ]);
        $row = $data->fetch(PDO::FETCH_LAZY);
    } catch(PDOException $e) {
        \Helpers\query\throwHttpError('query error', $e->getMessage(), '400 query error');
        exit;
    }
    
    if(!$row) {
        \Helpers\query\throwHttpError('user not found', 'Неправильный логин или пароль', '404 user not found');
        exit;
    }

    $_SESSION['user_id'] = $row->user_id;

    $_SESSION['user'] = [
        'firstname' => $row->user_firstname,
        'lastname' => $row->user_lastname,
        'login' => $row->user_login,
        'photo' => $row->user_photo ?: null,
        'bgimage' => $row->user_bgimage ?: null,
        'post_count' => 0,
        'rating' => 0
    ];

    try {
        $query = 'SELECT photo_id FROM photo WHERE user_id = ?';
        
        $data = $pdo->prepare($query);
        $data->execute([$_SESSION['user_id']]);
    } catch(PDOException $e) {
        \Helpers\query\throwHttpError('query error', $e->getMessage(), '400 query error');
        exit;
    }

    while($row = $data->fetch(PDO::FETCH_LAZY)) {
        $_SESSION['user']['post_count'] += 1;
    }

    setcookie('user-token', md5($_SESSION['user_id']), time()+60*60*24*30, '/');

    return ['login' => $_SESSION['login']];
}

function updateUser($fData) {
    try{
        $pdo = \Helpers\query\connectDB();
    } catch (PDOException $e) {
        \Helpers\query\throwHttpError('database error connect', $e->getMessage());
        exit;
    }

    if(!\Helpers\query\isExistsUserByLogin($pdo, $_SESSION['user']['login'])) {
        \Helpers\query\throwHttpError('user not exists: ', 'пользователь не найден');
        exit;
    }

    try {
        if(!$_FILES['edit-profile-avatar']['error']) {
            $avatar = \Helpers\files\loadfile('edit-profile-avatar')[0];

            if($_SESSION['user']['photo']) {
                unlink($_SERVER['DOCUMENT_ROOT'] . $_SESSION['user']['photo']);
            }
        }

        if(!$_FILES['edit-profile-bg']['error']) {
            $bgimage = \Helpers\files\loadfile('edit-profile-bg')[0];

            if($_SESSION['user']['bgimage']) {
                unlink($_SERVER['DOCUMENT_ROOT'] . $_SESSION['user']['bgimage']);
            }
        }

        $query = 'UPDATE "user" 
        SET user_firstname = :firstname, 
            user_lastname = :lastname, 
            user_photo = :photo, 
            user_bgimage = :bgimage
        WHERE user_id = :user_id';

        $newData = [
            'firstname' => $fData['firstname'] ?: $_SESSION['user']['firstname'],
            'lastname' => $fData['lastname'] ?: $_SESSION['user']['lastname'],
            'photo' => $avatar ?: $_SESSION['user']['photo'],
            'bgimage' => $bgimage ?: $_SESSION['user']['bgimage'],
            'user_id' => $_SESSION['user_id']
        ];
        
        $data = $pdo->prepare($query);
        $data->execute($newData);
    } catch(PDOException $e) {
        \Helpers\query\throwHttpError('query error', $e->getMessage(), '400 query error');
        exit;
    }

    $fieldsUpdate = ['firstname', 'lastname', 'photo', 'bgimage'];

    foreach($_SESSION['user'] as $code => $val) {
        if(in_array($code, $fieldsUpdate)) {
            $_SESSION['user'][$code] = $newData[$code];
        }
    }

    return [
        'login' => $_SESSION['user']['login']
    ];

}
