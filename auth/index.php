<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/init.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/templates/' . $config['template-name'] . '/header.php');


if($_POST['AUTH']['login-btn']){
    

    $res = $query->fetch(PDO::FETCH_LAZY);

    if (!$res) {
        echo '<p class="error">Неверные пароль или имя пользователя!</p>';
    } else {
        $_SESSION['user_id'] = $res->user_id;

        $_SESSION['user'] = [
            'firstname' => $res->user_firstname,
            'lastname' => $res->user_lastname,
            'login' => $res->user_login,
            'photo' => $res->user_photo ?: null,
            'bgimage' => $res->user_bgimage ?: null,
            'post_count' => 0,
            'rating' => 0
        ];

        $query = $db->prepare('SELECT photo_id FROM photo WHERE user_id = ?');
        $query->execute([$_SESSION['user_id']]);

        while($row = $query->fetch(PDO::FETCH_LAZY)) {
            $_SESSION['user']['post_count'] += 1;
        }

        setcookie('user-token', md5($_SESSION['user_id']), time()+60*60*24*30, '/');
    }
}
?>

<?if(!$_SESSION['user_id']):?>
    <form method="post" action="" name="auth-form" class='auth-form'>
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