# Example package for Evolution CMS 3.0 
Since most Laravel components are already in the core of Evolution CMS, it is logical to write additions for Evolution CMS according to the rules of Laravel: https://laravel.com/docs/8.x/packages
Most Laravel packages can be easy migrate to Evolution CMS.

##Contents:
- [Install](#Install)
- Package structure
- Assets
    - Chunks
    - Modules
    - Plugins
    - Snippets
    - TVs
- Migrations
- Public
- Views
- src
    - config
    - [Console](#Console)
    - Controllers
    - Routes
    - Middleware
    - Models
- [How Publish own package?](#how-publish-own-package)
- [How Migrate old solution for EVO 3.0](#how-migrate-old-solution-for-evo-30)


## Install
Run in you **core** folder:
1. ```php artisan package:installrequire evolution-cms/example-package "*" ```

2. ```php artisan vendor:publish --provider="EvolutionCMS\Example\ExampleServiceProvider"``` - если используется копирование файлов публичных и конфигов

3. ```php artisan migrate``` - если используются миграции

## Package structure

## Assets
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

## Migrations 


========================================================================================================================

#### Create migration file
run command from folder **core**: 
```
php artisan  make:migration test
```
This will create migration file in folder: **/core/database/migration**

#### Fill migration file
Open created file from **/core/database/migration**
and fill 2 function:
```
    //Create new Doc and add docId in global setting for use
    public function up()
    {
        $testDoc = SiteContent::create([
            'pagetitle' => 'test'
        ])->getKey();
        evo_update_config_settings('page_id_test', $testDoc);
    }
    
    //Delete created document and setting
    public function down()
    {
        $page_id_test = evolutionCMS()->getConfig('page_id_test');
        SiteContent::find($page_id_test)->forceDelete();
        evo_delete_config_settings('page_id_test');
    }
```

#### Run migration
run command from folder **core**:

```php artisan migrate```

#### Rollback last migration
run command from folder **core**:

```php artisan migrate:rollback```


========================================================================================================================


## Public
This folder contains all file what need use for frontend, like css, js, images. 

All file will move to assets folders when you run artisan publish:vendor command. 
For set what files you can move and where uses laravel functions: 
```html
 $this->publishes([__DIR__ . '/../public' => public_path('vendor/example')]);
```
More info you can find here: https://laravel.com/docs/8.x/packages#public-assets

## Views

## src
### config
### Console 
#### Artisan
Artisan is the command-line interface included with Laravel. It provides a number of helpful commands that can assist you while you build your application. More info you can find here: https://laravel.com/docs/8.x/artisan

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
### Routes
### Middleware

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


