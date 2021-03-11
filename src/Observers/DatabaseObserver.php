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
     * @param Database $database
     */
    public function created(Database $database)
    {
        config(['database.connections.onthefly' => [
            'driver' => $database->driver,
            'database' => $database->name,
            'host' => $database->host,
            'username' => $database->username,
            'password' => $database->password,
        ]]);

        $connection = DB::connection('onthefly');
        $tables = $connection->select('SHOW TABLES');

//        $tables = $connection->getAllTables();
        foreach ($tables as $table) {
            $this->addTable($database, $table->{'Tables_in_' . $database->name}, $connection);
//            $indexesFound = $sm->listTableIndexes($name);
//
//
//            foreach ($columns as $column) {
//                $type = $something->getColumnType($name, $column);
////                $tyoe = Schema::Connection('onthefly')->getColumnType($name, $column); // 'business' is your database connection
//            }
////            $columns = Schema::Connection('onthefly')->getColumnListing($name); // 'business' is your database connection

        }

        $test = '';
    }

    private function addTable(Database $database, string $table, $connection)
    {
        $foreigns = $connection->select(sprintf('SELECT * FROM information_schema.KEY_COLUMN_USAGE where TABLE_NAME = "%s"', $table));
        $schema = $connection->getSchemaBuilder();
//        $columns = $schema->getColumnListing($table);

        $columns = $connection->select(sprintf('SELECT *
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = "%s" and TABLE_SCHEMA = "%s"', $table, $database->name));

        $table = [
            'name' => $table,
            'label' => Str::ucfirst($table),
            'fields' => [],
            'relations' => [],
            'database_id' => $database->id,
        ];

        foreach ($columns as $column) {
            $column_name = $column->COLUMN_NAME;
            $column_type = $column->DATA_TYPE;
            $editable = Table::editable($column->EXTRA === 'auto_increment');

            $field  = [
                'name' => $column_name,
                'index' => true,
                'show' => true,
                'editable' => $editable
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
