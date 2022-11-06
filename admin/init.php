<?php
// проверка авторизации
session_start();

if(!isset($_SESSION['user_id']) && $_SERVER['REQUEST_URI'] != '/auth/'){
    header('Location: /auth/');
    exit();
} else if(isset($_SESSION['user_id']) && $_SERVER['REQUEST_URI'] == '/auth/') {
    header('Location: /');
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/api/helpers/logout.php');

$config = [
    'template-name' => 'main',
    'menu' => [
        'top' => 'main'
    ],
    // 'social-link' => [
    //     [
    //         'link' => 'vk.com',
    //         'icon' => '/upload/social/vk.png'
    //     ],
    //     [
    //         'link' => 'telegram.com',
    //         'icon' => '/upload/social/telegram.png'
    //     ],
    //     [
    //         'link' => 'github.com',
    //         'icon' => '/upload/social/github.png'
    //     ],
    // ]
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


// $prepared = $connection->prepare('SELECT * FROM public.user WHERE user_id=5 OR user_id=12');
// $result = $prepared->execute();
// if (!$result) {
//     exit();
// }