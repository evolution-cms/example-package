<?php namespace EvolutionCMS\Example;

use EvolutionCMS\ServiceProvider;

class ExampleServiceProvider extends ServiceProvider
{
    /**
     * Если указать пустую строку, то сниппеты и чанки будут иметь привычное нам именование
     * Допустим, файл test создаст чанк/сниппет с именем test
     * Если же указан namespace то файл test создаст чанк/сниппет с именем main#test
     * При этом поддерживаются файлы в подпапках. Т.е. файл test из папки subdir создаст элемент с именем subdir/test
     */
    protected $namespace = 'example';

    //add after line: protected $namespace
    protected $commands = [
        'EvolutionCMS\Example\Console\ExampleCommand',
    ];
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //add custom routes for package
        include(__DIR__.'/Http/routes.php');

        //Migration for create tables
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        //Custom Views
        $this->loadViewsFrom(__DIR__ . '/../views', 'example');

        //For publish css,js,img files
        $this->publishes([__DIR__ . '/../public' => public_path('vendor/example')]);

        //For use config
        $this->publishes([__DIR__ . '/config/example.php' => config_path('example.php', true)]);

        //MultiLang
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'example');
        //\Lang::addNamespace('example', __DIR__.'/../lang');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */

    public function register()
    {
        $this->loadSnippetsFrom(
            dirname(__DIR__). 'assets/snippets/',
            $this->namespace
        );

        $this->loadChunksFrom(
            dirname(__DIR__) . 'assets/chunks/',
            $this->namespace
        );

        $this->loadPluginsFrom(
            dirname(__DIR__) . 'assets/plugins/'
        );

        //use this code for each module what you want add
        $this->app->registerModule(
            'module from file',
            dirname(__DIR__).'assets/modules/module/module.php'
        );
    }
}