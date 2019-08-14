<?php namespace EvolutionCMS\SeriousCustom;

use EvolutionCMS\SeriousCustom\SeriousTemplateProcessor;
use EvolutionCMS\ServiceProvider;
use EvolutionCMS\TemplateProcessor;

class SeriousCustomTemplateServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('TemplateProcessor', function ($app) {
            return new SeriousTemplateProcessor($app);
        });
    }
}
