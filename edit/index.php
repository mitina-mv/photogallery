<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/init.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/templates/' . $config['template-name'] . '/header.php');

?>
<h2 class="main-caption">
    Редактировать страницу
</h2>


<form action="" method="POST" enctype="multipart/form-data" class='edit-profile' name='edit-profile'>
    <div class="error-block"></div>
    
    <div class="grid-container"> 
        <div class='grid-col-6'>
            <label for="edit-profile-avatar">Аватар</label>
            <label for="edit-profile-avatar" class="add-post__load-file input__load-file-block">
                <span>Добавьте фото</span>
            </label>
            <input type="file" name="edit-profile-avatar" id="edit-profile-avatar" class='input__load-file' accept="image/*">
        </div>      
        
        <div class='grid-col-6'>
            <label for="edit-profile-bg">Фоновая картинка</label>
            <label for="edit-profile-bg" class="add-post__load-file input__load-file-block">
                <span>Добавьте фото</span>
            </label>
            <input type="file" name="edit-profile-bg" id="edit-profile-bg" class='input__load-file' accept="image/*">
        </div>
    </div>

    <label for="edit-profile-firstname">Имя</label>
    <input type="text" name="edit-profile-firstname" value="">

    <label for="edit-profile-lastname">Фамилия</label>
    <input type="text" name="edit-profile-lastname" value="">
    
    <button type="submit" name="save-btn" class='btn btn-primary' value="save">Сохранить</button>
</form>

<script src="<?='/admin/assets/js/edituser.js'?>"></script>
