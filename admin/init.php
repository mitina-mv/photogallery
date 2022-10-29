<?php
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
    $connection = new PDO("pgsql:host={$host};port={$port};user={$user};password={$password};dbname={$dbname}");

    $connection->setAttribute(
        PDO::ATTR_ERRMODE, 
        PDO::ERRMODE_EXCEPTION
    );
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}


// $prepared = $connection->prepare('SELECT * FROM public.user WHERE user_id=5 OR user_id=12');
// $result = $prepared->execute();
// if (!$result) {
//     exit();
// }