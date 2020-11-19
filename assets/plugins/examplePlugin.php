<?php
//Work with  EVO and Laravel events:
Event::listen('evolution.OnWebPageComplete', function($params) {
    echo '<p>Hello Evo events.</p>';
});


