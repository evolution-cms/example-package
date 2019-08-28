<?php namespace EvolutionCMS\Example;

use EvolutionCMS\Models\SiteTemplate;
use EvolutionCMS\ServiceProvider;
use Event;

class ExampleServiceProvider extends ServiceProvider
{
    /**
     * Если указать пустую строку, то сниппеты и чанки будут иметь привычное нам именование
     * Допустим, файл test создаст чанк/сниппет с именем test
     * Если же указан namespace то файл test создаст чанк/сниппет с именем example#test
     * При этом поддерживаются файлы в подпапках. Т.е. файл test из папки subdir создаст элемент с именем subdir/test
     */

    protected $namespace = 'example';

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->loadSnippetsFrom(
            dirname(__DIR__). '/snippets/',
            $this->namespace
        );

        $this->loadChunksFrom(
            dirname(__DIR__) . '/chunks/',
            $this->namespace
        );

        //Тут работаем с событиями как EVO так и Laravel:
        foreach (glob(dirname(__DIR__) . '/plugins/*.php') as $file) {
            include $file;
        }

        $this->app->registerModule('module from file', dirname(__DIR__).'/module/module.php');
//        //Подключение контроллеров по названию шаблона
//        Event::listen('evolution.OnWebPageInit', function($params) {
//            $modx = EvolutionCMS();
//            $doc = $modx->getDocument($modx->documentIdentifier);
//            $templateAlias = SiteTemplate::select('templatealias')->find($doc['template'])->templatealias;
//            $className = '\EvolutionCMS\Example\\' . ucfirst($templateAlias) . 'Controller';
//            $controller = new $className();
//            $controller->render();
//        });


    }
}