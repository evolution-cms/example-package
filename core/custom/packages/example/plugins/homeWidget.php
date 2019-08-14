<?php

namespace EvolutionCMS\Custom;

use Event;
use Cache;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

Event::listen('evolution.OnManagerWelcomeHome', function(){


//    $widgets['welcome']['hide']='1';
//    $widgets['onlineinfo']['hide']='1';
//    $widgets['recentinfo']['hide']='1';
//    $widgets['news']['hide']='1';
//    $widgets['security']['hide']='1';

    if (isset($_GET['clc']) && $_GET['clc'] == 1) {
        Cache::flush();
        $evo = EvolutionCMS();
        $evo->clearCache('All');
    }

    $sizes = Cache::remember('LaravelCacheControl-sizes', 1, function () {
        return [
            'bootstrap'=>format_size(dirSize(EVO_STORAGE_PATH . 'bootstrap/')),
            'cache'=>format_size(dirSize(EVO_STORAGE_PATH . 'cache/')),
            'blade'=>format_size(dirSize(EVO_STORAGE_PATH . 'blade/')),

        ];
    });

    $widgets['LaravelCacheControl'] = array(
        'menuindex' =>'1',
        'id' => 'lcc',
        'cols' => 'col-sm-4',
        'icon' => 'fa-refresh',
        'title' => 'Laravel Cache Control',
        'body' => '<div class="card-body">	
                        <ul>
                            <li>Evolution Cache <strong>'.$sizes['bootstrap'].'</strong></li>
                            <li>Laravel Cache <strong>'.$sizes['cache'].'</strong></li>
                            <li>Blade Cache <strong>'.$sizes['blade'].'</strong></li>
                            <li> &nbsp;</li>
                            <li><a href="index.php?a=2&clc=1" class="btn btn-primary btn_lg">Clear сache</a></li>
                        </ul>		
					</div>'
    );
    return serialize($widgets);
});

function format_size($size) {
    $mod = 1024;
    $units = explode(' ','B KB MB GB TB PB');
    for ($i = 0; $size > $mod; $i++) {
        $size /= $mod;
    }

    return round($size, 2) . ' ' . $units[$i];
}

function dirSize($directory) {
    $size = 0;
    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file){
        $size+=$file->getSize();
    }
    return $size;
}

