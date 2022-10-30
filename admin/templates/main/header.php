<?php
foreach($config['menu'] as $menu){
    include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/menu/' . $menu . '.php');
}
if(isset($_SESSION['user_id'])){
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
            <div class="avatar-nums__item">
                <span class='avatar-nums__num'><?=$_SESSION['user']['post_count']?></span>
                <span>посты</span>
            </div>
            <div class="avatar-nums__item">
                <span class='avatar-nums__num'><?=$_SESSION['user']['rating']?></span>
                <span>оценка</span>
            </div>

        </div>

        <img class="avatar__photo" src="<?=$_SESSION['user']['photo']?>" alt="аватар">
        
        <div class="avatar__name">
            <span class="avatar__main-name"><?=$_SESSION['user']['lastname'] . $_SESSION['user']['firstname']?></span>
            <span class="avatar__subname"><?=$_SESSION['user']['login']?></span>
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

    <a href="?logout=true">Выход</a>
</header>

<?php }?>