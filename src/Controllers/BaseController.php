<?php namespace EvolutionCMS\Main\Controllers;

use Illuminate\Support\Facades\Cache;

class BaseController
{
    public $evo = [];
    public $data = [];

    public function __construct()
    {
        $this->evo = evo();
        ksort($_GET);
        $cacheid = sha1(json_encode($_GET));
        if ($this->evo->getConfig('enable_cache')) {
            $this->data = Cache::rememberForever($cacheid, function () {
                $this->globalElements();
                $this->render();
                return $this->data;
            });
        } else {
            $this->globalElements();
            $this->render();
        }
        $this->noCacheRender();
        $this->sendToView();
    }

    public function render()
    {
        // Here Code to be cached
    }

    public function noCacheRender()
    {
        // Here Code that will not be cached
    }

    public function globalElements()
    {
        // Here Code that will be available globally to all child controllers
    }

    public function sendToView()
    {
        $this->evo->addDataToView($this->data);
    }
}