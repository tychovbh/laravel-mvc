<?php

namespace Tychovbh\Mvc\Console\Commands;

use Illuminate\Console\Command;

class MvcRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mvc:repository {name : The name of the class.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Repository class';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $file = sprintf('%s/Repositories/%s.php', app('path'), $name);

        file_replace('Repository.php', [
            'EntityRepository' => $name
        ], $file, __DIR__ . '/..');

        $this->line('Repository created!');
    }
}
