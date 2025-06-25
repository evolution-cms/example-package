<?php namespace EvolutionCMS\Main\Console;

use Illuminate\Console\Command;

class ExampleCommand extends Command
{

    protected $signature = 'main:examplecommand';

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