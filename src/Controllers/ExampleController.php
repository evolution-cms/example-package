<?php

namespace EvolutionCMS\Example\Controllers;

use Illuminate\Support\Facades\Cache;

class ExampleController extends BaseController
{
    public function render()  //Пример для вывода данных
    {
        $this->data['test'] = 'test';
    }

}
