<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/init.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/templates/' . $config['template-name'] . '/header.php');

?>
<h2 class="main-caption">
    Добавьте пост
</h2>

<form action="" method="POST" enctype="multipart/form-data" class='add-post' name='add-post'>
    <label for="add-post-file">Фото</label>
    <label for="add-post-file" class="add-post__load-file input__load-file-block"><span>Добавьте фото</span></label>
    <input type="file" name="add-post-file" id="add-post-file" class='input__load-file' required accept="image/*">

    <label for="add-post-desc">Описание</label>
    <textarea name="desc" id="add-post-desc"></textarea>

    <button type="submit" name="save-btn" class='btn btn-primary' value="save">Сохранить</button>
</form>

<script src="<?='/admin/assets/js/addpost.js'?>"></script>