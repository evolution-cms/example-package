<?php namespace EvolutionCMS\Main;

use EvolutionCMS\ServiceProvider;

class ExampleServiceProvider extends ServiceProvider
{
    /**
     * If you specify an empty string, then snippets and chunks will have the usual naming
     * Let's say the file test will create a chunk/snippet named test
     * If namespace is specified, the test file will create a chunk/snippet with the name main#test
     * Files in subfolders are supported. That is, the file test from the subdir folder will create an element named subdir/test
     */
    protected $namespace = 'main';

    // Add after line: protected $namespace
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
        // Add custom routes for package
        include(__DIR__.'/Http/routes.php');

        // Migration for create tables
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        // Custom Views
        $this->loadViewsFrom(__DIR__ . '/../views', 'example');

        // Seeders
        $this->publishes([__DIR__ . '/../seeders' => EVO_CORE_PATH . 'database/seeders']);

        // For publish css,js,img files
        $this->publishes([__DIR__ . '/../public' => public_path('assets/vendor/example')]);

        // For use config
        $this->publishes([__DIR__ . '/config/example.php' => config_path('example.php', true)]);

        // MultiLang
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
        // Registering commands for artisan
        $this->commands($this->commands);

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