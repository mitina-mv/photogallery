<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/init.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/templates/' . $config['template-name'] . '/header.php');

?>
<h2 class="main-caption">
    Редактировать страницу
</h2>


<form action="" method="POST" enctype="multipart/form-data" class='edit-profile' name='edit-profile'>
    <label for="edit-profile-avatar">Аватар</label>
    <input type="file" name="edit-profile-avatar" id="edit-profile-avatar" accept="image/*">

    <label for="edit-profile-bg">Фоновая картинка</label>
    <input type="file" name="edit-profile-bg" id="edit-profile-bg" accept="image/*">

    <label for="edit-profile-firstname">Имя</label>
    <input type="text" name="edit-profile-firstname" value="">

    <label for="edit-profile-lastname">Фамилия</label>
    <input type="text" name="edit-profile-lastname" value="">
    
    <button type="submit" name="save-btn" value="save">Сохранить</button>
</form>

<script src="<?='/admin/assets/js/edituser.js'?>"></script>
