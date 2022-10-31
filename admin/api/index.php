<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

// Подключаем библиотеки и хелперы
// include_once 'lib/underscore.php';
include_once 'helpers/query.php';
include_once 'helpers/files.php';
session_start();

// Получаем данные из запроса
$data = \Helpers\query\getRequestData();
$router = $data['router'];

// Проверяем роутер на валидность
if (\Helpers\query\isValidRouter($router)) {

    // Подключаем файл-роутер
    include_once "routers/$router.php";

    // Запускаем главную функцию
    route($data);

} else {
    // Выбрасываем ошибку
    \Helpers\query\throwHttpError('invalid_router', 'router not found');
}
