<?php
// проверка авторизации
session_start();

if(!isset($_SESSION['user_id']) && strpos($_SERVER['REQUEST_URI'], '/auth/') === false) {
    header('Location: /auth/');
    exit();
} else if(isset($_SESSION['user_id']) && strpos($_SERVER['REQUEST_URI'], '/auth/') !== false) {
    header('Location: /');
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/api/helpers/logout.php');

$config = [
    'template-name' => 'main',
    'menu' => [
        'top' => 'main'
    ]
];
