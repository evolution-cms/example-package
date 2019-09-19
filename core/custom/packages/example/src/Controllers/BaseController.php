<?php

namespace EvolutionCMS\Example\Controllers;

use Illuminate\Support\Facades\Cache;

class BaseController
{
    public $data = [];

    public function __construct()
    {
        $this->evo = EvolutionCMS();
        ksort($_GET);
        $cacheid = md5(json_encode($_GET));
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

    }

    public function noCacheRender()
    {

    }

    public function globalElements()
    {
            $this->data['crumbs'] = json_decode($this->evo->runSnippet('DLCrumbs', ['api' => 1, 'showCurrent' => 1, 'addWhereList' => 'alias_visible=1']), true);
            $this->data['topmenu'] = json_decode($this->evo->runSnippet('DLMenu', ['parents' => 0, 'maxDepth' => 1, 'api' => 1]), true)[0];
    }

    public function sendToView()
    {
        $this->evo->addDataToView($this->data);
    }
}
