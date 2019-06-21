<?php

namespace EvolutionCMS\Custom;

class HomeController
{

    function __construct()
    {
        $this->modx = EvolutionCMS();
        $this->render();
    }

    public function render()
    {
        $this->modx->addDataToView([
            'topmenu'=>$this->topMenu()
        ]);
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
        return $docs;
    }

}