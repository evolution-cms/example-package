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


### Controllers
You can use controllers for prepare variables and sent to template and not use snippets in template (MVC pattern)

in file core/custom/packages/example/ExampleServiceProvider.php you can see how add controllers 

```php
//sent any data to template you can with this function
$modx->addDataToView([
   'foo'=>'bar'
]);
```
recommend place for Controllers in your package: ***src/Controllers***

### Models and Eloquent


### Chunks


### Snippets


### Plugins


### Modules


### Migration 

#### Create document
#### Create template
#### Create tv
#### Create module
#### Create new table


### Laravel Cache


### Laravel langs


### custom routing on FastRoute
you can see example in files: 
- core/custom/packages/example/plugins/FastRoute.plugin.php
- core/custom/packages/example/src/Controllers/ExampleApiController.php

best practice for build api 

### use .ENV


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