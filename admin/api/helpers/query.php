<?php

namespace Helpers\query;
use PDO;


// Получение данных из тела запроса
function getFormData($method) {

    // GET или POST: данные возвращаем как есть
    if ($method === 'GET') {
        $data = $_GET;
    } else if ($method === 'POST') {
        $data = $_POST;

    } else {
        // PUT, PATCH или DELETE
        $data = array();
        $exploded = explode('&', file_get_contents('php://input'));

        foreach($exploded as $pair) {
            $item = explode('=', $pair);
            if (count($item) == 2) {
                $data[urldecode($item[0])] = urldecode($item[1]);
            }
        }
    }

    // Удаляем параметр q
    unset($data['q']);

    return $data;
}


// Получаем все данные о запросе
function getRequestData() {
    $method = $_SERVER['REQUEST_METHOD'];

    // Разбираем url
    $url = (isset($_GET['q'])) ? $_GET['q'] : '';
    $url = trim($url, '/');
    $urls = explode('/', $url);

    return array(
        'method' => $method,
        'formData' => getFormData($method),
        'urlData' => $urls,
        'router' => $urls[0]
    );

}


// Подключение к БД
function connectDB() {
    $host = '127.0.0.1';
    $port = '5432';
    $user = 'postgres';
    $password = 'admin';
    $dbname = 'photogallery';


    $db = new PDO("pgsql:host={$host};port={$port};user={$user};password={$password};dbname={$dbname}");

    $db->setAttribute(
        PDO::ATTR_ERRMODE, 
        PDO::ERRMODE_EXCEPTION
    );

    return $db;
}


// Проверка роутера на валидность
function isValidRouter($router) {
    return in_array($router, array(
        'photo',
        'user',
    ));
}


// Проверка, существует ли категория с таким id
function isExistsCategoryById($pdo, $id) {
    $query = 'select id from categories where id=:id';
    $data = $pdo->prepare($query);
    $data->bindParam(':id', $id, PDO::PARAM_INT);
    $data->execute();

    return count($data->fetchAll()) === 1;
}


// Проверка, существует ли категория с таким названием
function isExistsCategoryByTitle($pdo, $title) {
    $query = 'select id from categories where category=:title';
    $data = $pdo->prepare($query);
    $data->bindParam(':title', $title);
    $data->execute();

    return count($data->fetchAll()) === 1;
}


// Проверка, существует ли бренд с таким id
function isExistsBrandById($pdo, $id) {
    $query = 'select id from brands where id=:id';
    $data = $pdo->prepare($query);
    $data->bindParam(':id', $id, PDO::PARAM_INT);
    $data->execute();

    return count($data->fetchAll()) === 1;
}


// Проверка, существует ли бренд с таким названием
function isExistsBrandByTitle($pdo, $title) {
    $query = 'select id from brands where brand=:title';
    $data = $pdo->prepare($query);
    $data->bindParam(':title', $title);
    $data->execute();

    return count($data->fetchAll()) === 1;
}


// Проверка, существует ли товар с таким id
function isExistsProductById($pdo, $id) {
    $query = 'select id from goods where id=:id';
    $data = $pdo->prepare($query);
    $data->bindParam(':id', $id, PDO::PARAM_INT);
    $data->execute();

    return count($data->fetchAll()) === 1;
}


// Проверка, существует ли товар с таким названием
function isExistsProductByTitle($pdo, $title) {
    $query = 'select id from goods where good=:title';
    $data = $pdo->prepare($query);
    $data->bindParam(':title', $title);
    $data->execute();

    return count($data->fetchAll()) === 1;
}


// Выводим 400 ошибку http-запроса
function throwHttpError($code, $message) {
    header('HTTP/1.0 400 Bad Request');
    echo json_encode(array(
        'code' => $code,
        'message' => $message
    ));
}