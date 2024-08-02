[English version](README_en.md)

# Приклад пакету Evolution CMS 3.x
Так як все більше компонентів Laravel в ядрі Evo, то всі основні речі для розробки своїх доповнень так само як у Laravel, тому рекомендую спочатку ознайомитися з: https://laravel.com/docs/11.x/packages
Так само не дуже складно портувати доповнення для Laravel у Evolution CMS, майже завжди це суто косметичні правки та допилювання вже під свої потреби.


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
Виконати з папки **core**:
1. ```php artisan package:installrequire evolution-cms/example-package "*" ``` - необхідно, щоб пакет був зареєстрований на сайті packagist.org

2. ```php artisan vendor:publish --provider="EvolutionCMS\Example\ExampleServiceProvider"``` - якщо використовується копіювання файлів публічних та конфігів

3. ```php artisan migrate``` - якщо використовуються міграції

4. ```php artisan db:seed --class=ExampleSeeder``` - якщо використовуються сіди

## Package structure
Рекомендована структура папок:

- Assets: для звичної структури, в цілому нам потрібні тільки плагіни та модулі і можна винести в корінь, сніпети та чанки можуть знадобитися тільки для того, щоб мігрувати старі доповнення
- Lang: для мультимовності
- Migrations: для міграцій, загалом це до створення таблиць, але можна робити і інші дії, наприклад через міграції можна створювати шаблони, тв, документи
- Seeders: для заповнення контентом, створення тв, шаблонів
- Public: всі файли, які потрібні публічно, в основному js, css, картинки
- Views: для шаблонів Blade
- src: тут все, що потрапляє в автолоад composer-a
  - config: Файли, які містять налаштування
  - Console: для консольних команд, які можна запускати через artisan або через cron
  - Controllers: тут створюємо контролери
  - Http: тут фаїл із кастомним роутингом
  - Middleware: створюємо свої Middleware якщо потрібно
  - Models: для моделок якщо створюємо якісь таблиці або перевизначаємо роботу вже готових моделей

Загалом структуру папок пакета можна міняти під себе, цей приклад носить рекомендований характер

## Assets
Нижче опис як працювати з тим, що лежить в папці assets

### Chunks
Є можливість створювати чанки у файлах, можна глянути тут: assets/chunks/

Для того, щоб чанки потрапили в систему потрібно додати до сервісу провайдера в register із завантаження:
```
$this->loadChunksFrom(
    dirname(__DIR__) . '/assets/chunks/',
    $this->namespace
);
```

Далі можемо використовувати де нам потрібно звертаючись до чанка на ім'я: namespace#chunkname:
```
$modx->getChunk('example#test');
```
Також можна чанки складати по папках вкладені:
```
$modx->getChunk('example#subdir\test');
```

Загалом у 3.х використання чанків не має сенсу, тому що це все логічніше і простіше робити через Blade


### Modules
Для того щоб модуль з'явився в Адмінці його потрібно зареєструвати додавши в сервіс провайдері в спосіб register:
```
$this->app->registerModule('Module from file', dirname(__DIR__).'/assets/modules/module/module.php');
```
ID модуля це **md5('Назва модуля')** - це дасть можливість зробити посилання на модуль, тому що модуль не створюється в базі і відповідно до цифрового id у нього немає.

### Plugins
Плагіни також можна створювати у файлах і їх потрібно реєструвати в сервіс-провайдері:
```
//this code work for add plugins to Evo
  $this->loadPluginsFrom(
    dirname(__DIR__) . '/assets/plugins/'
  );
```
Приклад можна глянути тут: assets/plugins/

### Snippets
Сніпети також можна створювати у файлах і їх потрібно реєструвати в сервіс-провайдері:
```
$this->loadSnippetsFrom(
    dirname(__DIR__). '/assets//snippets/',
    $this->namespace
);
```
Приклад можна глянути тут: example/snippets/

Якщо використовується namespace в пакеті, то назву сніпета необхідно писати так само з ним: namespace#snippetname:
```
evo()->runSnippet('example#test');
```
Також можна використовувати вкладені сніпети:
```
evo()->runSnippet('example#subdir\test');
```
Сніпети так само як і чанки не рекомендую для використання, тому що куди логічніше всю необхідну логіку вже писати в Контролерах

### TVs
Як правильно створювати ТВ можна глянути у цьому простому прикладі: https://github.com/extras-evolution/choiceTV/
Назва tv має бути за шаблоном:
```tvs/TVName/TVName.customtv.php```

У рамках Evo 3.х та пакету вирішуємо питання просто переміщенням папки з кастомним ТВ у потрібну папку через додавання інструкції до сервіс-провайдера:
```html
 $this->publishes([__DIR__ . '/../assets/tvs/TVName' => public_path('assets/tvs/TVName')]);
```
Далі після запуску команди **artisan vendor:publish** та вибору вказаного пакету всі файли скопіюються та все буде працювати

## Lang
Додаємо до сервіс провайдера boot()
```php
$this->loadTranslationsFrom(__DIR__.'/../lang', 'Main');
```
Посилання на те, звідки брати переклади і який у них namespace.
Далі в папці створюємо папки з мовами (en, ru, ітд.) і в них уже файли з перекладами

Після цього можемо використовувати в Blade:
```@lang('Main::main.welcome')```


## Migrations 
посилання на ті, звідки брати переклади і який у них namespace.
Далі в папці створюємо папки з мовами (en, ru, ітд.) і в них уже файли з перекладами

Після цього можемо використовувати Blade:
```$this->loadMigrationsFrom(__DIR__ . '/../migrations');```

Після встановлення доповнення виконуємо команду ```php artisan migrate```


## Seeders
Створюємо сід у папці seeders, додаємо запис про перенесення до сервіс-провайдера:
```$this->publishes([__DIR__ . '/../seeders' => EVO_CORE_PATH . 'database/seeders']);```

Після встановлення доповнення виконуємо команду ```php artisan db:seed --class=ExampleSeeder```


## Public
Ця папка містить все, що потрібно для фронтової частини: css, js, images.

Додаємо в сервіс-провайдері запис про те, що нам потрібно перенести: 
```php
 $this->publishes([__DIR__ . '/../public' => public_path('assets/vendor/example')]);
```
А в BLade вже прописуємо шляхи згідно з тим, куди файли будуть переміщені, для їх переміщення використовується команда **artisan vendor:publish**

Більше інформації можна знайти тут: https://laravel.com/docs/11.x/packages#public-assets

## Views
Додаємо в сервіс провайдер boot:
```php
 $this->loadViewsFrom(__DIR__ . '/../views', 'Main');
```
Після чого ми можемо використовувати шаблони Blade з урахуванням неймспейсів:
```php
return \View::make('Main::example', ['data'=>'1']);
```
Якщо нам потрібно внести зміни в шаблон blade то створюємо фаїл в основному місці **views** створивши там папку **vendor** і в ній папку з назвою пакету:
```/views/vendor/example/example.blade.php```

Так само планується завжди зміни базових шаблонів з пакета, то можна відразу перенести їх у потрібне місце:
```php
$this->publishes([__DIR__.'/../views' => public_path('views/vendor/example')]);
```
Детальніше читаємо в документації Laravel: https://laravel.com/docs/11.x/packages#views


## src
У цій папці у нас лежить всі файли які потрапляють в автолоад composer-а, в цілому це так само можна змінити якщо потрібно у файлі composer.json

### config
Робота з конфігами така ж як у Laravel:
- https://laravel.com/docs/11.x/packages#configuration
- https://laravel.com/docs/11.x/packages#default-package-configuration

Ми можемо створити для пакета свої налаштування і після них додати в системні, щоб їх можна було змінювати.

### Console 
Artisan – це інтерфейс командного рядка, включений до Evolutions CMS. Він надає ряд корисних команд, які можуть допомогти вам при створенні програми. Детальнішу інформацію ви можете знайти тут: https://laravel.com/docs/11.x/artisan

#### Как использовать Artisan:
запустити **php artisan** із папки **core**:
```console
php artisan
```
для того, щоб побачити всі команди

#### Как создать свою консольную команду:
Створюємо файл: **core/custom/packages/example/src/Console/ExampleCommand.php**
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
Додаємо до сервіс провайдера: **core/custom/packages/example/src/ExampleServiceProvider.php**
```
    //добавить после строки: protected $namespace
    protected $commands = [
        'EvolutionCMS\Example\Console\ExampleCommand',
    ];
```

Також у методі register нашого сервісу провайдера вказуємо:
```php
//регистрация команд для artisan
$this->commands($this->commands);
```

Тепер можна використати:
```php artisan example:examplecommand```

Даний функціонал зручний для завдань які необхідно виконувати по крону або довгі і простіше через консоль щоб не було лімітів на час виконання, які зазвичай є якщо виконуємо якісь роботи через браузер.

### Controllers
Контролери створюємо у папці src/Controllers
Можна переглянути приклади, які є в поточному пакеті.

Контролери набагато зручніше у використанні ніж сніпети для основної роботи. Але думаю ті хто вже дійшов до OOP та MVC розуміють навіщо це треба якщо ні то гуглим OOP, MVC та вивчаємо

### Routes
Для використання кастомних роутингів (наприклад ajax відповіді) додаємо в сервіс-провайдер boot():
```php
include(__DIR__.'/Http/routes.php');
```
Як працювати з роутингом читаємо тут: https://laravel.com/docs/11.x/routing
Також рекомендую ознайомитися з цим прикладом у якому створюємо форму і відправляємо її: https://gist.github.com/Dmi3yy/10e5a004bb77a72a4446ac1ad4c2d9ad

### Middleware
Якщо ви розумієте, що таке Middleware то і знаєте як з ними працювати :)
Детальніше читаємо тут:
https://laravel.com/docs/11.x/middleware

Із системних на даний момент є CheckAuthToken: https://github.com/evolution-cms/evolution/blob/3.x/core/src/Middleware/CheckAuthToken.php (удобно использовать если дружим EVO 3.0 c SPA)

```php
Route::middleware(['EvolutionCMS\\Middleware\\CheckAuthToken'])->group(function () {
     Route::get('/secureuserinfo', [EvolutionCMS\Example\Controllers\ExampleApiController::class, '`getInfo`']);
});
```

### Models
Модельки складаємо до папки: ***src/Models***
Можна проглянути які вже є моделі в Evo за замовчуванням: https://github.com/evolution-cms/evolution/tree/3.x/core/src/Models
all works same https://laravel.com/docs/11.x/eloquent


## How Publish own package 
Опублікувати для того щоб можна було знайти в консольному extras який з'явився в evo 3.0, зробили так що б можна було скриптами налаштовувати установку EVO c будь-яким набором доповнень без ручного додавання цих.

1. Створюємо пакет на Github (можна клонувати поточний), використовуємо префікс *evocms-* у назві пакета, або щонайменше пишемо **Evocms** у файлі composer.json у тегу description. Це допоможе знайти всі пакети, які доступні для встановлення через Composer і зроблені для Evolution CMS https://packagist.org/?query=evocms
2. Реєструємо на сайті https://packagist.org (загалом це працює для будь-якого php рішення)
3. Пишемо мені лист на пошту dmi3yy@evo.im якщо хочете, щоб доповнення було доступне **Evo artisan Extras**:
   я сколоную в один з репозиторіїв для того, щоб було зручніше стежити і доповнювати:
- https://github.com/evolution-cms-extras - використовується для готових до використання компонентів
- https://github.com/evolution-cms-packages - використовуються як заготовки для того, щоб далі на базі них створювати сайт

Активних авторів запрошуватиму до команди evolution-cms-extras та evolution-cms-packages щоб самі могли доповнювати та розвивати доповнення.

## Як старі доповнення адаптувати під EVO 3.x
Загалом вивчивши дані приклад, ви вже повинні розуміти як це зробити.

Але якщо ви хочете зробити все швидко, але халтурно щоб доповнення з'явилося в [Evo artisan Extras](https://github.com/evolution-cms-extras) то найпростіше глянути як я мігрував DocLister:

1. Створив composer.json фаїл: https://github.com/evolution-cms-extras/DocLister/blob/master/composer.json
2. Створив сервіс провайдер: https://github.com/evolution-cms-extras/DocLister/blob/master/src/DocListerServiceProvider.php
3. Переніс з папки інстал сніпети в пакет так що б вони відразу працювали (тут важливо, щоб пакет був з порожнім namespace): https://github.com/evolution-cms-extras/DocLister/tree/master/snippets
4. Опублікував пакет як описано вище: [Publish package](#how-publish-own-package)

Все тепер можна встановлювати пакет і використовувати його.