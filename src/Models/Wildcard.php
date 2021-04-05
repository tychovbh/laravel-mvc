<?php

namespace Tychovbh\Mvc\Models;

use Database\Factories\WildcardFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Tychovbh\Mvc\Helpers\WildcardManager;

/**
 * Class Wildcard
 * @package Tychovbh\Mvc\Models
 * @property Database $database
 * @property Table $database_table
 */
class Wildcard extends Model
{
    /**
     * @var WildcardManager
     */
    protected $manager;

    /**
     * Wildcard constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->manager = self::manager(request());

        if ($this->manager) {
            $this->setUp($this->manager->database, $this->manager->table);
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

            if (Arr::get($field, 'searchable', 'false') === 'true') {
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
     * The Relations
     * @param Request $request
     * @return array
     */
    public static function relations(Request $request): array
    {
        $manager = self::manager($request);
        return $manager ? $manager->table->getAttribute('relations') : [];
    }

    /**
     * The Index fields
     * @param Request $request
     * @return array
     */
    public static function info(Request $request): array
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
    public static function manager(Request $request): ?WildcardManager
    {

        $connection = $request->route('connection');
        $table = $request->route('table');

        if (!$table || !$connection || !user()->id) {
            return null;
        }

        return wildcard_manager($connection, $table);
    }

    /**
     * The Wildcard Database.
     * @return Database
     */
    public function getDatabaseAttribute(): Database
    {
        return $this->manager ? $this->manager->database : new Database;
    }

    /**
     * The Wildcard Database Table.
     * @return Table
     */
    public function getDatabaseTableAttribute(): Table
    {
        return $this->manager ? $this->manager->table : new Table;
    }
}
