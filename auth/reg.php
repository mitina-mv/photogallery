<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/init.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/templates/' . $config['template-name'] . '/header.php');
?>

<?if(!$_SESSION['user_id']):?>
    <form method="post" action="" name="reg-form" class='reg-form'>
        <div class="error-block"></div>

        <div class="form-element">
            <label>Имя</label>
            <input type="text"
                name="firstname"
                pattern="[А-яа-я]+"
                required
                value='<?=$_REQUEST['firstname']?>' />
        </div>

        <div class="form-element">
            <label>Фамилия</label>
            <input type="text"
                name="lastname"
                pattern="[А-яа-я]+"
                required
                value='<?=$_REQUEST['lastname']?>' />
        </div>

        <div class="form-element">
            <label>Login</label>
            <input type="text"
                name="login"
                pattern="[a-z0-9]+"
                required
                value='<?=$_REQUEST['login']?>' />
        </div>

        <div class="form-element">
            <label>Пароль</label>
            <input type="password"
                name="password"
                class="password"
                required />
        </div>

        <div class="form-element">
            <label>Повторите пароль</label>
            <input type="password"
                name="password-repeat"
                class="password-repeat"
                required />
        </div>

        <div class='form__spam-check'>
            <span class="spam-check__line"></span>
            <span class="spam-check__block"></span>
        </div>

        <button type="submit"
            name="reg-btn"
            value="registration">Зарегистироваться</button>

        <div class="form-links">
            <span class="form-links__item">
                Уже есть аккаунт? 
                <a href="/auth/" class="auth-form__link form-link">Авторизация</a>
            </span>
        </div>
    </form>
<?else:?>
    <p>Привет, <?=$_SESSION['user']['firstname']?>! Вы уже авторизованы.</p>
<?endif;?>

<script src="/admin/assets/js/reg.js"></script>