# Example package for Evolution CMS 2.0 

## Install
- Upload files to your project 
- Run **composer upd** from core folder
- Create new template with templatealias **example**
- Set this template to Document what you want test


### Templates
Linking templates to the document without EVO admin panel

To work, we need to create a folder views in the root of the site, in which we will have templates. Actually and everything, no additional actions in the form of installing anything is required.

Searches for templates in such order:

- tpl-3_doc-5.blade.php - use this if resource id=5 and resource template=3;
- doc-5.blade.php - use this if resource id=5;
- tpl-3.blade.php - use this if resource template=3;
- example.blade.php - use this if resource templatealias = example;

if not found any match use template from DB

Template work with Blade, docs here: https://laravel.com/docs/6.0/blade


### Controllers and MVC 
Best way use this packages: https://github.com/Ser1ous/seriousCustomTemplate
You can use controllers for prepare variables and sent to template and not use snippets in template (MVC pattern)

If you won't use controllers but want send data to template, ffor example from plugin or snippet you can use this code:
```php
$modx->addDataToView([
   'foo'=>'bar'
]);
```
place for Controllers in your package: ***src/Controllers***

p.s. after add come new Controllers need run composer upd

### Models and Eloquent
place for Models in your package: ***src/Models***
All default tables already have Models you can see here: **/core/src/Models/**
all works same https://laravel.com/docs/6.0/eloquent

p.s. after add come new Models need run composer upd

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
            $evo = evolutionCMS();
            SiteContent::where('id',$evo->getConfig('page_id_test'))->delete();
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

### custom routing on FastRoute
you can see example in files: 
- core/custom/packages/example/plugins/FastRoute.plugin.php
- core/custom/packages/example/src/Controllers/ExampleApiController.php

best practice for build api 

### use .ENV
**core/custom/.env.example**

need install **vlucas/phpdotenv** see **/core/custom/composer.json.example**


### Artisan
run **artisan** from **core** folder

**Available commands:**
```console
Usage:
  command [options] [arguments]

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  clear-compiled          Remove the compiled class file
  help                    Displays help for a command
  list                    Lists commands
  migrate                 Run the database migrations
 cache
  cache:clear             Flush the application cache
  cache:forget            Remove an item from the cache
 db
  db:seed                 Seed the database with records
 doc
  doc:list{--parent-id=*  Documents from the site_content table
 migrate
  migrate:fresh           Drop all tables and re-run all migrations
  migrate:install         Create the migration repository
  migrate:refresh         Reset and re-run all migrations
  migrate:reset           Rollback all database migrations
  migrate:rollback        Rollback the last database migration
  migrate:status          Show the status of each migration
 tpl
  tpl:list                Templates from the site_template table
 tv
  tv:list                 TV lists
 vendor
  vendor:publish          Publish any publishable assets from vendor packages
 view
  view:clear              Clear all compiled view files
```

Also you can add own artisan commands:
@TODO: Serious