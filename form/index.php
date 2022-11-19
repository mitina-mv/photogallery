<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/init.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/templates/' . $config['template-name'] . '/header.php');

?>
<h2 class="main-caption">
    Добавьте пост
</h2>

<form action="" method="POST" enctype="multipart/form-data" class='add-post' name='add-post'>
    <label for="add-post-file">Фото</label>
    <input type="file" name="add-post-file" id="add-post-file" required accept="image/*">

    <label for="add-post-desc">Описание</label>
    <textarea name="desc" id="add-post-desc"></textarea>

    <button type="submit" name="save-btn" value="save">Сохранить</button>
</form>

<script src="<?='/admin/assets/js/addpost.js'?>"></script>