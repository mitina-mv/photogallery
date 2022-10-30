<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/init.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/templates/' . $config['template-name'] . '/header.php');

if($_POST['AUTH']['login-btn']){
    $login = htmlspecialchars($_POST['AUTH']['login']);
    $password = md5(md5($_POST['AUTH']['password'] . "-pwd"));
    
    $query = $db->prepare('SELECT * FROM "user" WHERE user_password = :password AND user_login = :login');

    $query->execute([
        'password' => $password,
        'login' => $login,
    ]);

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
    }
}
?>

<?if(!$_SESSION['user_id']):?>
    <form method="post"
        action=""
        name="auth-form"
        class='auth-form'>
        <div class="form-element">
            <label>Login</label>
            <input type="text"
                name="AUTH[login]"
                pattern="[a-zA-Z0-9]+"
                required
                value='<?=$_REQUEST['AUTH']['login']?>' />
        </div>
        <div class="form-element">
            <label>Пароль</label>
            <input type="password"
                name="AUTH[password]"
                required />
        </div>
        <button type="submit"
            name="AUTH[login-btn]"
            value="login">Log In</button>
    </form>
<?else:?>
    <p>Привет, <?=$_SESSION['user']['firstname']?>! Вы уже авторизованы.</p>
<?endif;?>