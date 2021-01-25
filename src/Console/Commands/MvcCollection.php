<?php

namespace Tychovbh\Mvc\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MvcCollection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mvc:collection {name : The name of the collection.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Collection class';

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
        $filename = time() . '_' . Str::camel($name);
        $file = sprintf('%s/database/collections/%s.php', base_path(), $filename);

        file_replace('Collection.php', [
            'Entity' => ucfirst($name),
            '{table}' => strtolower($name)
        ], $file, __DIR__ . '/..');

        $this->line('Collection created!');
    }
}
