<?php

namespace Tychovbh\Mvc\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tychovbh\Mvc\Repositories\Repository;

class MvcCollections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mvc:collections';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update database tables via configuration file';

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
        $path = base_path() . "/database/collections/*.php";
        $collections = [];
        foreach(glob($path) as $class) {
            include_once $class;
        }

        foreach ($collections as $collection) {
            try {
                $this->saveCollection($collection);
            } catch (\Exception $exception) {
                $this->error($exception->getMessage());
            }
        }

        $this->info('MVC Collections updated!');
    }

    /**
     * Save collection
     * @param array $collection
     * @throws \Exception
     */
    public function saveCollection(array $collection)
    {
        $repository = $this->repository($collection);

        if (Arr::has($collection, 'relations')) {
            foreach ($collection['relations'] as $relation) {
                DB::table($relation)->truncate();
            }
        }

        foreach ($collection['items'] as $item) {
            $repository->saveOrUpdate($collection['update_by'], $item[$collection['update_by']], $item);
        }
    }

    /**
     * Get repository by table name
     * @param array $collection
     * @return Repository
     * @throws \Exception
     */
    private function repository(array $collection): Repository
    {
        if (Arr::has($collection, 'repository')) {
            return new $collection['repository'];
        }
        $resourceRepository = project_or_package_class(
            'Repository',
            'App\Repositories\\' . Str::studly(Str::singular($collection['table'])) . 'Repository'
        );

        return new $resourceRepository;
    }
}
