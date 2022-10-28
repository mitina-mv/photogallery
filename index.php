<?php
echo "Hello world</br>";

$host = '127.0.0.1';
$port = '5432';
$user = 'postgres';
$password = 'admin';
$dbname = 'photogallery';
		
$dsn = "pgsql:host={$host};port={$port};user={$user};password={$password};dbname={$dbname}";
$connection = new PDO($dsn);
$connection->setAttribute(
    PDO::ATTR_ERRMODE, 
    PDO::ERRMODE_EXCEPTION
);

$prepared = $connection->prepare('SELECT * FROM public.user WHERE user_id=5 OR user_id=12');
$result = $prepared->execute();
if ($result) {
    $data = $prepared->fetchAll();
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}