<?php

namespace Tychovbh\Mvc\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;


class MvcRoute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mvc:route {name : The name of the class.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Route class';

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
        $file = sprintf('%s/Routes/%s.php', app('path'), $name);

        $route_name = Str::slug(Str::plural(str_replace('Route', '', $name)), '_');

        file_replace('Route.php', [
            'EntityRoute' => $name,
            '{{name}}' =>$route_name
        ], $file, __DIR__ . '/..');

        $this->line('Route created!');
    }
}
