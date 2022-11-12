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

$host = '127.0.0.1';
$port = '5432';
$user = 'postgres';
$password = 'admin';
$dbname = 'photogallery';
	
try {
    $db = new PDO("pgsql:host={$host};port={$port};user={$user};password={$password};dbname={$dbname}");

    $db->setAttribute(
        PDO::ATTR_ERRMODE, 
        PDO::ERRMODE_EXCEPTION
    );
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}