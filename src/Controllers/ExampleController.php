<?php namespace EvolutionCMS\Main\Controllers;

class ExampleController extends BaseController
{
    public function render()
    {
        parent::render();

        // Example for data output
        $this->data['test'] = 'test';
    }
}