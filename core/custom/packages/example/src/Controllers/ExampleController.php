<?php

namespace EvolutionCMS\Custom;

class ExampleController
{

    function __construct()
    {
        $this->modx = EvolutionCMS();
    }

    public function render()
    {
        $this->menu('2');
    }

    private function menu($parents){
        $docs = $this->modx->runSnippet('DLMenu',['parents'=>$parents, 'api'=>'1']);
        $menu = json_decode($docs);
        $this->modx->addDataToView(['menu'=>$menu['0']]);
    }


    private function topMenu()
    {
        $params = [
            'parents'=>'0',
            'tpl'=>'',
            'saveDLObject'=>'_DL'
        ];
        $this->modx->runSnippet('DocLister', $params);
        $DocLister = $this->modx->getPlaceholder('_DL');
        $docs = $DocLister->docsCollection()
            ->map(function(array $doc){
                return array_only($doc, [
                    'id',
                    'alias',
                    'pagetitle',
                    'createdon',
                    'parent',
                    'type'
                ]);
            })
            ->getValues();


        $this->modx->addDataToView(['topmenu'=>$docs]);
    }


}