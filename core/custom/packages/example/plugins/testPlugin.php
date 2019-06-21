<?php
//Тут работаем с событиями как EVO так и Laravel:
Event::listen('evolution.OnWebPageComplete', function($params) {
    echo '<p>Hello Evo events.</p>';
});