<?php

namespace Tychovbh\Mvc\Observers;

use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tychovbh\Mvc\Models\Database;
use Tychovbh\Mvc\Models\Table;
use Tychovbh\Mvc\Repositories\TableRepository;

/**
 * @property TableRepository tables
 */
class DatabaseObserver
{
    public function __construct(TableRepository $tables)
    {
        $this->tables = $tables;
    }

    /**
     * Creating event.
     * @param Database $database
     */
    public function creating(Database $database)
    {
        if (!$database->user_id) {
            $database->user_id = user()->id;
        }
    }

    /**
     * Created event.
     * @param Database $database
     */
    public function created(Database $database)
    {
        $connection = connection($database, 'onthefly');
        $tables = $connection->select('SHOW TABLES');

        foreach ($tables as $table) {
            $this->addTable($database, $table->{'Tables_in_' . $database->name}, $connection);
        }
    }

    private function addTable(Database $database, string $table, $connection)
    {
//        $foreigns = $connection->select(sprintf('SELECT * FROM information_schema.KEY_COLUMN_USAGE where TABLE_NAME = "%s"', $table));
        $columns = $connection->select(sprintf('SELECT *
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = "%s" and TABLE_SCHEMA = "%s"', $table, $database->name));

        $label = Str::ucfirst($table);

        $table = [
            'name' => $table,
            'label' => $label,
            'create_title' => 'Create ' . $label,
            'edit_title' => 'Edit ' . $label,
            'fields' => [],
            'relations' => [],
            'database_id' => $database->id,
        ];

        foreach ($columns as $column) {
            $column_name = $column->COLUMN_NAME;
            $column_type = $column->DATA_TYPE;
            $editable = Table::editable($column->EXTRA === 'auto_increment', $column_type);

            $field  = [
                'name' => $column_name,
                'label' => Str::studly(Str::ucfirst($column_name)),
                'index' => true,
                'show' => true,
                'searchable' => true,
                'fillable' => $editable
            ];

            if ($editable) {
                $element = Table::element($column_type);
                $field['element'] = $element;
                $field['properties'] = Table::{$element . 'Properties'}($column_name, $column_type, $column->IS_NULLABLE === 'YES');
            }

            $table['fields'][] = $field;
        }

        $this->tables->save($table);
    }
}
