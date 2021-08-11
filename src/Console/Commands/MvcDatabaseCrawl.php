<?php

namespace Tychovbh\Mvc\Console\Commands;

use Illuminate\Console\Command;
use Tychovbh\Mvc\Jobs\DatabaseCrawl;
use Tychovbh\Mvc\Models\Database;

class MvcDatabaseCrawl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mvc-database:crawl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl Datbase';

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
     * @throws \Exception
     */
    public function handle()
    {
        $name = $this->ask('Which database?');
        $database = Database::where('name', $name)->firstOrFail();

        dispatch(new DatabaseCrawl($database));

        return 0;
    }
}
