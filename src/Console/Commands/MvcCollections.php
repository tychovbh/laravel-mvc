<?php


namespace Tychovbh\Mvc\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Tychovbh\Mvc\Collections\Collection;
use Tychovbh\Mvc\Repositories\Repository;

class MvcCollections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mvc:collections {--class=}';

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
        // $path = '/home/vagrant/bespokeweb/packages/laravel-mvc/database/collections/*.php';

        foreach(glob($path) as $file) {
            include_once $file;
            try {
                $classes = get_declared_classes();
                $class = end($classes);
                $class = prev($classes);

                if ($this->option('class') && $this->option('class') !== $class) {
                    continue;
                }

                $collection = new $class;

                if ($this->isComplete($class, $collection)) {
                    $this->saveCollection($collection);
                }
            } catch (\Exception $exception) {
                $this->error($exception->getMessage() . ' in: ' . $class);
            }
        }

        $this->info('MVC Collections updated!');
    }

    /**
     * Check if collection class is complete
     * @param string $class
     * @param Collection $collection
     * @return bool
     */
    private function isComplete(string $class, Collection $collection): bool
    {
        if (!$collection->table()) {
            $this->error('Property $table is missing in: ' . $class);
            return false;
        }

        if (!$collection->updateBy()) {
            $this->error('Property $update_by is missing in: ' . $class);
            return false;
        }

        if (!$collection->records()) {
            $this->error('No records returned in: ' . $class);
            return false;
        }

        return true;
    }

    /**
     * Save collection
     * @param Collection $collection
     * @throws \Exception
     */
    private function saveCollection(Collection $collection)
    {
        $repository = $this->repository($collection->table());

        foreach ($collection->records() as $item) {
            $repository->saveOrUpdate($collection->updateBy(), $item[$collection->updateBy()], $item);
        }
    }

    /**
     * Get repository by table name
     * @param array $collection
     * @return Repository
     * @throws \Exception
     */
    private function repository(string $table): Repository
    {
        $resourceRepository = project_or_package_class(
            'Repository',
            'App\Repositories\\' . Str::studly(Str::singular($table)) . 'Repository'
        );

        return new $resourceRepository;
    }
}
