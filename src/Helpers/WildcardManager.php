<?php
namespace Tychovbh\Mvc\Helpers;

use Illuminate\Support\Arr;
use Tychovbh\Mvc\Models\Database;
use Tychovbh\Mvc\Models\Table;

class WildcardManager
{
    /**
     * @var array
     */
    protected static $instances = [];

    /**
     * @var Database
     */
    public $database;

    /**
     * @var Table
     */
    public $table;

    /**
     * DatabaseHelper constructor.
     * @param string $database
     * @param string $table
     */
    public function __construct(string $database, string $table)
    {
        $user = request()->user();

        $user_id = $database === config('database.connections.mysql.database') ? 1 : $user->id;

        $this->database = Database::where('name', $database)->where('user_id', $user_id)->first();

        if ($this->database->id) {
            $this->table = Table::where('database_id', $this->database->id)->where('name', $table)->first();
        }
    }

    /**
     * Initialize WildcardManager
     * @param string $database
     * @param string $table
     * @return WildcardManager
     */
    public static function init(string $database, string $table): WildcardManager
    {
        $key = $database . '_' . $table;
        if (!Arr::has(self::$instances, $key)) {
            Arr::set(self::$instances, $key, new self($database, $table));
        }
        return self::$instances[$key];
    }
}
