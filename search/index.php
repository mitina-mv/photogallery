<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/init.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/templates/' . $config['template-name'] . '/header.php');

?>
<h2 class="main-caption">
    Поиск
</h2>

<form action="" method="post" class='search-form'>
    <input type="text" name="search-text" id="search-text">
    <button type="submit">Поиск</button>
</form>

<div class="search-result"></div>

<script src="/admin/assets/js/search.js"></script>