<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/init.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/templates/' . $config['template-name'] . '/header.php');
?>

<?if(!$_SESSION['user_id']):?>
    <form method="post" action="" name="auth-form" class='auth-form'>
        <img src="/admin/assets/image/logo.svg" alt="log">

        <div class="error-block"></div>
        
        <div class="form-element">
            <label>Login</label>
            <input type="text"
                name="login"
                pattern="[a-zA-Z0-9]+"
                required
                value='<?=$_REQUEST['login']?>' />
        </div>
        <div class="form-element">
            <label>Пароль</label>
            <input type="password"
                name="password"
                required />
        </div>
        <button type="submit"
            name="login-btn"
            class='btn btn-primary'
            value="login">Войти</button>

        <div class="form-links">
            <span class="form-links__item">
                Впервые здесь? 
                <a href="/auth/reg.php" class="auth-form__link form-link">Регистрация</a>
            </span>
        </div>
    </form>

<?else:?>
    <p>Привет, <?=$_SESSION['user']['firstname']?>! Вы уже авторизованы.</p>
<?endif;?>

<script src="/admin/assets/js/auth.js"></script>