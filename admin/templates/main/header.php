<?php
// проверка авторизации
session_start();
// if(!isset($_SESSION['user_id'])){
//     header('Location: /auth/');
//     exit;
// }

include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/menu/' . $config['menu']['top'] . '.php');
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заголовок страницы</title>
    <script src="/admin/templates/main/script.js"></script>
</head>
<body>

<header>
    <div class="avatar">
        <div class="avatar__nums">

        </div>

        <img class="avatar__photo" src="" alt="аватар">
        
        <div class="avatar__name">

        </div>

    </div>

    <nav class='main-menu'>
        <?php 
            foreach($arSiteMenu[$config['menu']['top']] as $item):?>
                <a class='main-menu__item' href="<?=$item['link']?>">
                    <?=$item['title']?>
                </a>
        <?php endforeach;?>
    </nav>
</header>