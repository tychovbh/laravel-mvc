<?php

namespace Tychovbh\Mvc\Models;

use Illuminate\Support\Arr;

class Wildcard extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (!request()) {
            return;
        }

        $table = request()->route('table');
        $connection = request()->route('connection');

        if ($table && $connection) {
            $user_id = (int)request('user_id');
            $this->setUp($user_id, $connection, $table);
        }
    }

    /**
     * Setup Wildcard model with proper db connection and table
     * @param int $user_id
     * @param string $database
     * @param string $table
     */
    public function setUp(int $user_id, string $database, string $table)
    {
        $connection = $database . '.' . $table;

        $database = Database::where('name', $database)->where('user_id', $user_id)->first();
        $table = Table::where('database_id', $database->id)->where('name', $table)->first();

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
}
