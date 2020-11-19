<?php

namespace EvolutionCMS\Example\Controllers;

use \EvolutionCMS\Models\SiteContent;
/**
 * Class ExampleApiController
 * @package EvolutionCMS\Custom
 */
class ExampleApiController
{

    public function getDocuments()
    {
        $docs = SiteContent::where('parent', 0)
            ->orderBy('pagetitle', 'asc')
            ->get();
        return $docs;
    }

    public function getInfo(){
        return \Response::json($this->getDocuments());
    }


}