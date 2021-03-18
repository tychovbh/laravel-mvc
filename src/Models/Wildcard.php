<?php

namespace Tychovbh\Mvc\Models;

use Database\Factories\WildcardFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Tychovbh\Mvc\Helpers\WildcardManager;

class Wildcard extends Model
{
    /**
     * Wildcard constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $manager = self::manager(request());

        if ($manager) {
            $this->setUp($manager->database, $manager->table);
        }

        parent::__construct($attributes);
    }

    /**
     * Setup Wildcard model with proper db connection and table
     * @param Database $database
     * @param Table $table
     */
    public function setUp(Database $database, Table $table)
    {
        $connection = $database->name . '.' . $table->name;

        $fillables = [];
        $columns = [];

        foreach ($table->fields as $field) {
            if (Arr::get($field, 'fillable', false)) {
                $fillables[] = $field['name'];
            }

            if (Arr::get($field, 'searchable', false)) {
                $columns[] = $field['name'];
            }
        }

        $this->setTable($table->name);
        $this->fillables(...$fillables);
        $this->columns(...$columns);
        connection($database, $connection);
        $this->setConnection($connection);
    }

    /**
     * Create a Wildcard Factory
     * @param Database $database
     * @param Table $table
     * @return WildcardFactory
     */
    public static function factory(Database $database, Table $table): WildcardFactory
    {
        return new WildcardFactory($database, $table);
    }

    /**
     * The Index fields
     * @param Request $request
     * @return array
     */
    public static function index(Request $request): array
    {
        $manager = self::manager($request);
        return $manager ? $manager->table->index_fields->toArray() : [];
    }

    /**
     * The Index fields
     * @param Request $request
     * @return array
     */
    public static function show(Request $request): array
    {
        $manager = self::manager($request);
        return $manager ? $manager->table->show_fields->toArray() : [];
    }

    /**
     * The Index fields
     * @param Request $request
     * @return array
     */
    public static function meta(Request $request): array
    {
        $manager = self::manager($request);
        return $manager ? [
            'name' => $manager->table->name,
            'label' => $manager->table->label,
        ] : [];
    }

    /**
     * The Wildcard Manager
     * @param Request $request
     * @return WildcardManager|null
     */
    private static function manager(Request $request): ?WildcardManager
    {
        $connection = $request->route('connection');
        $table = $request->route('table');

        if (!$table || !$connection || !user()->id) {
            return null;
        }

        return wildcard_manager($connection, $table);
    }
}
