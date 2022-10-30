<?php
if($_GET['logout'] == "true"){
    unset($_SESSION['user']);
    unset($_SESSION['user_id']);
}