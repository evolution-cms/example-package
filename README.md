# Example package for Evolution CMS 3.0 


Contents:
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
- How Publish own package?


## Install
Run in you **core** folder:
1. ```php artisan package:installrequire evolution-cms/example-package "*" ```

2. ```php artisan vendor:publish --provider="EvolutionCMS\Example\ExampleServiceProvider"``` - если используется копирование файлов публичных и конфигов

3. ```php artisan migrate``` - если используются миграции

## Package structure

## Assets
### Chunks
### Modules
### Plugins
### Snippets
### TVs

## Migrations 
## Public
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

 




### Docs
Linking templates to the document without EVO admin panel

To work, we need to create a folder views in the root of the site, in which we will have templates. Actually and everything, no additional actions in the form of installing anything is required.

Searches for templates in such order:

- tpl-3_doc-5.blade.php - use this if resource id=5 and resource template=3;
- doc-5.blade.php - use this if resource id=5;
- tpl-3.blade.php - use this if resource template=3;
- example.blade.php - use this if resource templatealias = example;

if not found any match use template from DB

Template work with Blade, docs here: https://laravel.com/docs/6.0/blade



### Models and Eloquent
place for Models in your package: ***src/Models***
All default tables already have Models you can see here: **/core/src/Models/**
all works same https://laravel.com/docs/6.0/eloquent

p.s. after add come new Models need run composer upd

### Packages
For create package use Artisan 

### Chunks
You can create chunks from files:
```
$this->loadChunksFrom(
    dirname(__DIR__) . '/chunks/',
    $this->namespace
);
```
in file core/custom/packages/example/ExampleServiceProvider.php you can see how this work

See sample of chunks in folder: core/custom/packages/example/chunks/

If you use package with namespace you need write snippet like: namespace#chunkname: 
```
$modx->getChunk('example#test');
```
You can use subdir, put chunk file in subdir after that call:
```
$modx->getChunk('example#subdir\test');
```

### Snippets
You can create snippets from files:
```
$this->loadSnippetsFrom(
    dirname(__DIR__). '/snippets/',
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

### Plugins
You can create plugins from files:
```
//this code work for add plugins to Evo
  $this->loadPluginsFrom(
    dirname(__DIR__) . '/plugins/'
  );
```
in file core/custom/packages/example/ExampleServiceProvider.php you can see how this work

See sample of plugins in folder: core/custom/packages/example/plugins/

### Modules
You can create module from files, without adding in manager panel:
```
$this->app->registerModule('module from file', dirname(__DIR__).'/module/module.php');
```
in file core/custom/packages/example/ExampleServiceProvider.php you can see how this work

### Migration 

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



### Laravel Cache
All works same https://laravel.com/docs/6.0/cache
```
$items = Cache::rememberForever($cacheid, function () use ($params) {
    return foo($params);
});
```

for clear laravel cache you can use plugin on event(OnCacheUpdate or OnSiteRefresh), or write own logic:
```
Event::listen('evolution.OnCacheUpdate', function($params) {
    Cache::flush();
});
```

### Custom routing on FastRoute
you can see example in files: 
- core/custom/packages/example/plugins/FastRoute.plugin.php
- core/custom/packages/example/src/Controllers/ExampleApiController.php

best practice for build api 

### use .ENV
**core/custom/.env.example**





