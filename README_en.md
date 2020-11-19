# Example package for Evolution CMS 3.0 
Since most Laravel components are already in the core of Evolution CMS, it is logical to write additions for Evolution CMS according to the rules of Laravel: https://laravel.com/docs/8.x/packages
Most Laravel packages can be easy migrate to Evolution CMS.

## Contents:
- [Install](#Install)
- [Package structure](#package-structure)
- [Assets](#assets)
    - [Chunks](#chunks)
    - [Modules](#modules)
    - [Plugins](#plugins)
    - [Snippets](#snippets)
    - [TVs](#tvss)
- [Lang](#lang)
- [Migrations](#migrations)
- [Public](#public)
- [Views](#views)
- [src](#src)
    - [config](#config)
    - [Console](#console)
    - [Controllers](#controllers)
    - [Routes](#routes)
    - [Middleware](#middleware)
    - [Models](#models)
- [How Publish own package?](#how-publish-own-package)
- [How Migrate old solution for EVO 3.0](#how-migrate-old-solution-for-evo-30)


## Install
Run in you **core** folder:
1. ```php artisan package:installrequire evolution-cms/example-package "*" ```

2. ```php artisan vendor:publish --provider="EvolutionCMS\Example\ExampleServiceProvider"``` - если используется копирование файлов публичных и конфигов

3. ```php artisan migrate``` - если используются миграции

## Package structure
This structure recommended for use, but you can use any what you want.

- Assets: folder for files what connected   



## Assets
Used for convenience when migrating from older versions and for a more familiar naming and location of elements.

### Chunks
You can create chunks from files:
```
$this->loadChunksFrom(
    dirname(__DIR__) . '/assets/chunks/',
    $this->namespace
);
```
in file core/custom/packages/example/ExampleServiceProvider.php you can see how this work

See sample of chunks in folder: core/custom/packages/example/assets/chunks/

If you use package with namespace you need write snippet like: namespace#chunkname: 
```
$modx->getChunk('example#test');
```
You can use subdir, put chunk file in subdir after that call:
```
$modx->getChunk('example#subdir\test');
```

### Modules
You can create module from files, without adding in manager panel:
```
$this->app->registerModule('Module from file', dirname(__DIR__).'/assets/modules/module/module.php');
```
in file core/custom/packages/example/ExampleServiceProvider.php you can see how this work

### Plugins
You can create plugins from files:
```
//this code work for add plugins to Evo
  $this->loadPluginsFrom(
    dirname(__DIR__) . '/assets/plugins/'
  );
```
in file core/custom/packages/example/ExampleServiceProvider.php you can see how this work

See sample of plugins in folder: core/custom/packages/example/assets/plugins/

### Snippets
You can create snippets from files:
```
$this->loadSnippetsFrom(
    dirname(__DIR__). '/assets//snippets/',
    $this->namespace
);
```
in file core/custom/packages/example/ExampleServiceProvider.php you can see how this work

See sample of snippets in folder: core/custom/packages/example/snippets/

If you use package with namespace you need write snippet like: namespace#snippetname: 
```
$modx->runSnippet('example#test');
```
You can use subdir, put snippet file in subdir after that call:
```
$modx->runSnippet('example#subdir\test');
```

### TVs
Create folder and files similar https://github.com/extras-evolution/choiceTV/: 
```tvs/TVName/TVName.customtv.php``` 
with all logic what you need and add rules for publications: 

```html
 $this->publishes([__DIR__ . '/../assets/tvs' => public_path('assets/tvs')]);
```
after public with artisan public:vendor all will work

## Lang
Add in Service Provider in boot()
```php
$this->loadTranslationsFrom(__DIR__.'/../lang', 'example');
```
in Folder lang you need folders for langs like: en, ru, etc. and in folder php file with translations: 

After that you can use: 
```@lang('example::main.welcome')```


## Migrations 
All work like in Laravel https://laravel.com/docs/8.x/migrations
Put file with migration for package in folder migrations and set in Service Provider: 
```$this->loadMigrationsFrom(__DIR__ . '/../migrations');```

When you install package, need run from **core** folder ```php artisan migrate```


## Public
This folder contains all file what need use for frontend, like css, js, images. 

All file will move to assets folders when you run artisan publish:vendor command. 
For set what files you can move and where uses laravel functions in Service Provider: 
```php
 $this->publishes([__DIR__ . '/../public' => public_path('assets/vendor/example')]);
```
More info you can find here: https://laravel.com/docs/8.x/packages#public-assets

## Views
Add to Service Provider boot:
```php
 $this->loadViewsFrom(__DIR__ . '/../views', 'example');
```
Now you can use views with namespace: 
```php
return \View::make('example::example', ['data'=>'1']);
```
If need overriding package views, you can put view file to path:
```/views/vendor/example/example.blade.php```

And if you publish views you can do that with this code in Service Provider boot: 
```php
$this->publishes([__DIR__.'/../views' => public_path('views/vendor/example')]);
```
Full information you can read here: https://laravel.com/docs/8.x/packages#views


## src
In this place put all code what need be autoloaded with composer

### config
Read Laravel docs: 
- https://laravel.com/docs/8.x/packages#configuration
- https://laravel.com/docs/8.x/packages#default-package-configuration

### Console 
Artisan is the command-line interface included in Evolutions CMS. It provides a number of helpful commands that can assist you while you build your application. More info you can find here: https://laravel.com/docs/8.x/artisan

#### How use artisan:
run **artisan** from **core** folder:
```console
php artisan
```
for see all Available commands

#### How Create console command:
Also you can add own artisan commands:
create file: **core/custom/packages/example/src/Console/ExampleCommand.php**
```
<?php
namespace EvolutionCMS\Example\Console;

use Illuminate\Console\Command;

class ExampleCommand extends Command
{

    protected $signature = 'example:examplecommand';

    protected $description = 'ExampleCommand';

    public function __construct()
    {
        parent::__construct();
        $this->evo = EvolutionCMS();
    }

    public function handle()
    {
        echo 'Hello Word';
    }
}
```
add in file: **core/custom/packages/example/src/ExampleServiceProvider.php**
```
    //add after line: protected $namespace
    protected $commands = [
        'EvolutionCMS\Example\Console\ExampleCommand',
    ];
```

Now you can use: 
```php artisan example:examplecommand```


### Controllers
Put Controllers in src/Controllers 
for sample see in code.


### Routes
If your package contains routes, you may load them using this code in ServiceProvider boot(): 
```php
include(__DIR__.'/Http/routes.php');
```
How work with routes, you can read here: https://laravel.com/docs/8.x/routing

### Middleware
You can put Middleware in folder src/Middleware and use that
https://laravel.com/docs/8.x/middleware

In Evolution CMS exist Middleware for check user auth token https://github.com/evolution-cms/evolution/blob/3.x/core/src/Middleware/CheckAuthToken.php:

```php
Route::middleware(['EvolutionCMS\\Middleware\\CheckAuthToken'])->group(function () {
     Route::get('/secureuserinfo', [EvolutionCMS\Example\Controllers\ExampleApiController::class, '`getInfo`']);
});
```


### Models
place for Models in your package: ***src/Models***
All default tables already have Models you can see here: **/core/src/Models/**
all works same https://laravel.com/docs/6.0/eloquent

p.s. after add some new Models need run composer upd



## How Publish own package 
1. Create own package on github (you can clone this for that), use prefix *evocms-* in package name, or write Evocms in file composer.json description tag. This help find all packages on https://packagist.org/?query=evocms
2. Register own package on https://packagist.org (need for use with composer)
3. Write me dmi3yy@evo.im if you want add your package to **Evo artisan Extras**: 
- https://github.com/evolution-cms-extras
- https://github.com/evolution-cms-packages



## How Migrate old solution for EVO 3.0
This sample package build for that,  you can fully rewrite for new rules. 

### But you can do fast migrate for use in [Evo artisan Extras](https://github.com/evolution-cms-extras)


1. Create composer.json file,  sample: https://github.com/evolution-cms-extras/DocLister/blob/master/composer.json
2. Create and set Service provider, sample: https://github.com/evolution-cms-extras/DocLister/blob/master/src/DocListerServiceProvider.php
3. Move plugins,snippets,chunks from install to folder in package, sample: https://github.com/evolution-cms-extras/DocLister/tree/master/snippets
4. [Publish package](#how-publish-own-package)  


