<?php

namespace EvolutionCMS\Example;

use Illuminate\Support\Facades\Cache;

class ExampleController extends BaseController
{
    public function render()  //Пример для вывода данных
    {
        $this->data['test'] = 'test';
    }

    public function noCacheRender()
    {
        $this->data['menu'] = $this->menu(2);
        $this->data['enable_cache'] = $this->evo->getConfig('enable_cache');
    }


    private function menu($parents){
        $cacheid = 'menu'.$this->evo->documentIdentifier;
        //set cache by docId and 10 min
        $menu = Cache::remember($cacheid, 10, function () use ($parents) {
           return json_decode($this->evo->runSnippet('DLMenu',['parents'=>$parents, 'api'=>'1']));
        });
        return $menu['0'];
    }
}
