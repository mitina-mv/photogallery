<?php
if($_GET['logout'] == "true"){
    $_SESSION = [];
    header('Location: /auth/');
}