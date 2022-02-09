[English version](README_en.md)

# Пример пакета для Evolution CMS 3.0 
Так как все больше компонентов Laravel в ядре Evo, то все основные вещи для разработки своих дополнений так же как в Laravel, поэтому настоятельно рекомендую сначала ознакомится с :  https://laravel.com/docs/8.x/packages
Так же не сильно сложно портировать дополнения для Laravel в Evolution CMS, почти всегда это чисто косметические правки и допиливание уже под свои нужды. 


## Содержание:
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
- [Seeders](#seeders)
- [Public](#public)
- [Views](#views)
- [src](#src)
    - [config](#config)
    - [Console](#console)
    - [Controllers](#controllers)
    - [Routes](#routes)
    - [Middleware](#middleware)
    - [Models](#models)
- [Как опубликовать свой пакет?](#how-publish-own-package)
- [Как мигрировать старые дополнения в EVO 3.0](#how-migrate-old-solution-for-evo-30)


## Install
Выполнить из папки **core**:
1. ```php artisan package:installrequire evolution-cms/example-package "*" ``` - необходимо что б пакет был зарегистирован на сайте packagist.org

2. ```php artisan vendor:publish --provider="EvolutionCMS\Example\ExampleServiceProvider"``` - если используется копирование файлов публичных и конфигов

3. ```php artisan migrate``` - если используются миграции

4. ```php artisan db:seed --class=ExampleSeeder``` - если используются сиды

## Package structure
Рекомендованная структура папок:

- Assets: для привычной структуры, в целом нам нужны только плагины и модули и можно вынести в корень, сниппеты и чанки могут понадобиться только для того что б мигрировать старые дополнения
- Lang: для мультиязычности
- Migrations: для миграций, в целом это для создания таблиц, но можно так же делать и другие действия, к примеру через миграции можно создавать шаблоны, тв, документы
- Seeders: для заполнения контентом, создания тв, шаблонов
- Public: все файлы которые нужны публично, в основном js, css, картинки
- Views: для шаблонов Blade
- src: тут все что попадает в автолоад composer-a
    -config: Файлы которые содержать настройки
    -Console: для консольных команд которые можно запускать через artisan или через cron
    -Controllers: тут создаем контроллеры
    -Http: тут фаил с кастомным роутингом
    -Middleware: создаем свои Middleware если нужно 
    -Models: для моделек если создаем какие то таблицы или переопределяем работу уже готовых моделей 

В целом структуру папок пакета можно менять под себя, данный пример носит рекомендованный характер

## Assets
Ниже описание как работать с тем что лежит в папке assets

### Chunks
Есть возможность создавать чанки в файлах, можно глянуть тут: assets/chunks/

Для того что б чанки попали в систему нужно добавить в сервис провайдере в register из загрузку: 
```
$this->loadChunksFrom(
    dirname(__DIR__) . '/assets/chunks/',
    $this->namespace
);
```

Далее можем использовать где нам нужно обращаясь к чанку по имени: namespace#chunkname: 
```
$modx->getChunk('example#test');
```
Так же можно чанки складывать по папкам вложенны:
```
$modx->getChunk('example#subdir\test');
```

В целом в 3.0 использование чанков не имеет смысла, так как это все логичней и проще делать через Blade


### Modules
Для того что б модуль появился в Админке его необходимо зарегистировать добавив в сервис провайдере в метод register:
```
$this->app->registerModule('Module from file', dirname(__DIR__).'/assets/modules/module/module.php');
```
ID модуля это **md5('Название модуля')** - это даст возможность сделать ссылку на модуль, так как модуль не создается в базе и соответственно цифрового id у него нет.

### Plugins
Плагины так же можно создавать в файлах и их нужно регистировать в сервиспровайдере:
```
//this code work for add plugins to Evo
  $this->loadPluginsFrom(
    dirname(__DIR__) . '/assets/plugins/'
  );
```
Пример можно глянуть тут: assets/plugins/

### Snippets
Снипеты так же можно создавать в файлах и их нужно регистировать в сервиспровайдере:
```
$this->loadSnippetsFrom(
    dirname(__DIR__). '/assets//snippets/',
    $this->namespace
);
```
Пример можно глянуть тут: example/snippets/

Если используется namespace в пакете то название сниппета необходимо писать так же с ним: namespace#snippetname: 
```
$modx->runSnippet('example#test');
```
Так же можно использовать вложенные сниппеты:
```
$modx->runSnippet('example#subdir\test');
```
Сниппеты так же как и чанки не рекомендую для использования, так как куда логичней всю необходимую логику уже писать в Контроллерах

### TVs
Как правильно создавать ТВ можно глянуть в этом простом примере: https://github.com/extras-evolution/choiceTV/
Название tv должно быть по шаблону: 
```tvs/TVName/TVName.customtv.php```
 
В рамках Evo 3.0 и пакета решаем вопрос просто перемещением папки с кастомным ТВ в нужную папку через добавление инструкции в сервиспровайдер:  
```html
 $this->publishes([__DIR__ . '/../assets/tvs/TVName' => public_path('assets/tvs/TVName')]);
```
Далее после запуска команды **artisan vendor:publish** и выбора указанного пакета все файлы скопируются и все будет работать

## Lang
Добавляем в сервис провайдере в boot()
```php
$this->loadTranslationsFrom(__DIR__.'/../lang', 'example');
```
ссылку на то откуда брать переводы и какой у них namespace. 
Далее в папке создаем папки с языками( en, ru, итд.) и в них уже файлы с переводами 

После можем использовать в Blade: 
```@lang('example::main.welcome')```


## Migrations 
Все работает точно так же как и в Laravel https://laravel.com/docs/8.x/migrations
Создаем фаил миграции в папке миграций и прописываем подключение пути для миграций в сервис провайдере: 
```$this->loadMigrationsFrom(__DIR__ . '/../migrations');```

После установки дополнения выполням команду ```php artisan migrate```


## Seeders
Создаем сид в папке seeders, добавляем запись о переносе в сервис-провайдер:
```$this->publishes([__DIR__ . '/../seeders' => EVO_CORE_PATH . 'database/seeders']);```

После установки выполням команду ```php artisan db:seed --class=ExampleSeeder```


## Public
Данная папка содержит все что нужно для фронтовой части: css, js, images. 

Добавляем в сервиспровайдере запись о том что нам нужно перенести: 
```php
 $this->publishes([__DIR__ . '/../public' => public_path('assets/vendor/example')]);
```
А в BLade прописываем уже пути согласно тому куда файлы будут перемещены, для их перемещения используется команда **artisan vendor:publish**

Больше информации можно найти тут: https://laravel.com/docs/8.x/packages#public-assets

## Views
Добавляем в сервис провайдер в boot:
```php
 $this->loadViewsFrom(__DIR__ . '/../views', 'example');
```
После чего мы можем использовать шаблдоны blade c учетом неймспейсов: 
```php
return \View::make('example::example', ['data'=>'1']);
```
Если же нам нужно внести изменения в шаблон blade то создаем фаил в основном месте **views** создав там папку **vendor** и в ней папку с названием пакета :
```/views/vendor/example/example.blade.php```

Так же планируется всегда изменения базовых шаблонов из пакета то можно сразу перенести их в нужное место: 
```php
$this->publishes([__DIR__.'/../views' => public_path('views/vendor/example')]);
```
Детальней читаем в документации Laravel: https://laravel.com/docs/8.x/packages#views


## src
В этой папке у нас лежит сервиспровайдер а так же все файлы которые попадают в автолоад composer-а, в целом это так же можно изменить если нужно в файле composer.json

### config
Работа с конфигами такая же как в Laravel: 
- https://laravel.com/docs/8.x/packages#configuration
- https://laravel.com/docs/8.x/packages#default-package-configuration

Мы можем создать для пакета свои настройки и после их добавить в системные что б их можно было изменять

### Console 
Artisan - это интерфейс командной строки, включенный в Evolutions CMS. Он предоставляет ряд полезных команд, которые могут помочь вам при создании приложения. Более подробную информацию вы можете найти здесь: https://laravel.com/docs/8.x/artisan

#### Как использовать Artisan:
запустить  **php artisan** из папки **core**:
```console
php artisan
```
для того что б увидеть все команды

#### Как создать свою консольную команду:
Создаем фаил: **core/custom/packages/example/src/Console/ExampleCommand.php**
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
Добавляем в сервис провайдере: **core/custom/packages/example/src/ExampleServiceProvider.php**
```
    //добавить после строки: protected $namespace
    protected $commands = [
        'EvolutionCMS\Example\Console\ExampleCommand',
    ];
```

Также в методе register нашего сервис провайдера указываем:
```php
//регистрация команд для artisan
$this->commands($this->commands);
```

Теперь можно использовать: 
```php artisan example:examplecommand```

Данный функционал удобен для задач которые необходимо выполнять по крону или долгие и проще через консоль что б не было лимитов на время выполнения, которые обычно есть если выполняем какие то работы через браузер.

### Controllers
Контроллеры создаем в папке src/Controllers 
Можно глянуть примеры которые есть в текущем пакете. 

Контроллеры гараздо удобней в использовании чем снипеты для основной работы. Но думаю те кто уже дошел до OOP и MVC понимают зачем это надо если нет то гуглим OOP, MVC и изучаем


### Routes
Для использования кастомны роутингов (например ajax ответы) добавляем в сервиспровайдер boot(): 
```php
include(__DIR__.'/Http/routes.php');
```
Как работать с роутингом читаем тут: https://laravel.com/docs/8.x/routing
Так же рекомендую ознакомится с вот этим примером в котором создаем форму и отправляем ее: https://gist.github.com/Dmi3yy/10e5a004bb77a72a4446ac1ad4c2d9ad

### Middleware
Если вы понимаете что такое Middleware то и знаете как с ними работать :)
Детальней читаем тут: 
https://laravel.com/docs/8.x/middleware

Из системных на текущий момент есть CheckAuthToken: https://github.com/evolution-cms/evolution/blob/3.x/core/src/Middleware/CheckAuthToken.php (удобно использовать если дружим EVO 3.0 c SPA)

```php
Route::middleware(['EvolutionCMS\\Middleware\\CheckAuthToken'])->group(function () {
     Route::get('/secureuserinfo', [EvolutionCMS\Example\Controllers\ExampleApiController::class, '`getInfo`']);
});
```


### Models
Модельки складываем в папку: ***src/Models***
Можно глянуть какие есть уже модели в Evo по умолчанию: https://github.com/evolution-cms/evolution/tree/3.x/core/src/Models
all works same https://laravel.com/docs/6.0/eloquent


## How Publish own package 
Опубликовать для того что б можно было найти в консольном extras который появился в evo 3.0, сделали так что б можно было скриптами настраивать установку EVO c любым набором дополнений без ручного добавления оных.

1. Создаем пакет на Github (можно клонировать текущий), используем префикс *evocms-* в названии пакета, или как минимум пишем **Evocms** в файле composer.json в теге description. Это поможет находить все пакеты которые доступны для установки через Composer и сделанны для Evolution CMS https://packagist.org/?query=evocms
2. Регистрируем на сайте https://packagist.org (в целом это работает для любого php решения)
3. Пишем мне письмо на почту dmi3yy@evo.im если хотите что б было дополнение доступно **Evo artisan Extras**: 
я сколонирую в один из репозиториев для того что б было удобней следить и дополнять:
- https://github.com/evolution-cms-extras  - используется для готовых к использованию компонентов
- https://github.com/evolution-cms-packages - используются как заготовки для того что б дальше на базе них создавать сайт

Активных авторов буду приглашать в команду evolution-cms-extras и evolution-cms-packages что б сами могли дополнять и развивать дополнения. 


## Как старые дополнения адаптировать под EVO 3.0
В целом изучив данные пример вы уже должны понимать как это сделать.

###№ Но если вы хотите сделать все быстро, но халтурно что б дополнение появилось в [Evo artisan Extras](https://github.com/evolution-cms-extras) то проще всего глянуть как я мигрировал DocLister: 

1. Создал composer.json фаил: https://github.com/evolution-cms-extras/DocLister/blob/master/composer.json
2. Создал сервис провайдер: https://github.com/evolution-cms-extras/DocLister/blob/master/src/DocListerServiceProvider.php
3. Перенес из папки инстал снипеты в пакет так что б они сразу работали (тут важно что б пакет был с пустым namespace): https://github.com/evolution-cms-extras/DocLister/tree/master/snippets
4. Опубликовал пакет как описано выше: [Publish package](#how-publish-own-package)  

Все, теперь можно устанавливать пакет и использовать его. 

