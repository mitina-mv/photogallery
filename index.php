<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/admin/init.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/templates/' . $config['template-name'] . '/header.php');

?>

<h2 class="main-caption">
    Ваши фото
</h2>

<div class="posts"></div>
<div class="hystmodal post-modal" id="post-detail" aria-hidden="true">
    <div class="hystmodal__wrap">
        <div class="hystmodal__window" role="dialog" aria-modal="true">
            <button data-hystclose class="hystmodal__close">Закрыть</button>
            
            <div class="post-modal__body"></div>
        </div>
    </div>
</div>


