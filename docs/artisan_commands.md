## Artisan
Artisan is the command-line interface included with Laravel. It provides a number of helpful commands that can assist you while you build your application. More info you can find here: https://laravel.com/docs/8.x/artisan

### How use artisan:
run **artisan** from **core** folder:
```console
php artisan
```
for see all Available commands

### How Create command:
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