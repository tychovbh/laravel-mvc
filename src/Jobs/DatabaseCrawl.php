<?php

namespace Tychovbh\Mvc\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Tychovbh\Mvc\Models\Database;
use Tychovbh\Mvc\Models\Table;
use Tychovbh\Mvc\Repositories\TableRepository;

class DatabaseCrawl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var array
     */
    protected $tables = [];

    /**
     * Create a new job instance.
     *
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tables = [];
    }

    /**
     * Execute the job.
     *
     * @param TableRepository $tableRepository
     */
    public function handle(TableRepository $tableRepository)
    {
        $database = $this->database;
        $connection = connection($database, 'onthefly');
        $tables = $connection->select('SHOW TABLES');

        foreach ($tables as $table) {
            $this->addTable($database, $table->{'Tables_in_' . $database->name}, $connection);
        }

        foreach ($tables as $table) {
            $this->addRelations($database, $table->{'Tables_in_' . $database->name}, $connection);
        }

        foreach ($this->tables as $table) {
            $tableRepository->save($table);
        }
    }


    /**
     * Add Table.
     * @param Database $database
     * @param string $table
     * @param ConnectionInterface $connection
     */
    private function addTable(Database $database, string $table, ConnectionInterface $connection)
    {
        $label = Str::ucfirst($table);
        $this->tables[$table] = [
            'name' => $table,
            'label' => $label,
            'create_title' => 'Create ' . $label,
            'edit_title' => 'Edit ' . $label,
            'fields' => $this->addFields($database, $table, $connection),
            'relations' => [],
            'database_id' => $database->id,
        ];
    }

    /**
     * Add Table Fields.
     * @param Database $database
     * @param string $table
     * @param ConnectionInterface $connection
     * @return array
     */
    private function addFields(Database $database, string $table, ConnectionInterface $connection)
    {
        $fields = [];
        $columns = $connection->select(sprintf('SELECT *
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = "%s" and TABLE_SCHEMA = "%s"', $table, $database->name));

        foreach ($columns as $column) {
            $column_name = $column->COLUMN_NAME;
            $column_type = $column->DATA_TYPE;
            $editable = Table::editable($column->EXTRA === 'auto_increment', $column_type);
            $label = Str::title(str_replace('-', ' ', $column_name));

            $field = [
                'name' => $column_name,
                'label' => $label,
                'index' => 'true',
                'show' => 'true',
                'searchable' => 'true',
                'fillable' => $editable
            ];

            if ($editable) {
                $element = Table::element($column_type);
                $field['element'] = $element;
                $options = $this->options($column_type, $column->COLUMN_TYPE);
                $field['properties'] = Table::{$element . 'Properties'}(
                    $column_name,
                    $label,
                    $column_type,
                    $column->IS_NULLABLE === 'YES',
                    $options
                );
            }

            $fields[] = $field;
        }

        return $fields;
    }

    /**
     * Field options
     * @param string $column_type
     * @param string $options
     * @return array
     */
    private function options(string $column_type, string $options): array
    {
        if ($column_type !== 'enum') {
            return [];
        }

        $options = explode(',', str_replace(['enum(', ')', '\''], '', $options));

        return array_map(function ($option) {
            return [
                'value' => $option,
                'label' => Str::title(str_replace('-', ' ', $option)),
            ];
        }, $options);
    }

    /**
     * Add Table Relations.
     * @param Database $database
     * @param string $table
     * @param ConnectionInterface $connection
     */
    private function addRelations(Database $database, string $table, ConnectionInterface $connection)
    {
        $foreigns = $connection->select(sprintf('SELECT *
                FROM information_schema.KEY_COLUMN_USAGE
                where TABLE_NAME = "%s"
                AND TABLE_SCHEMA = "%s"', $table, $database->name));

        foreach ($foreigns as $foreign) {
            if (!$foreign->REFERENCED_TABLE_NAME) {
                continue;
            }

            $reference = $foreign->REFERENCED_TABLE_NAME;

            $this->tables[$table]['relations'][] = [
                'name' => $reference,
                'label' => Str::ucfirst($reference),
                'column' => $foreign->COLUMN_NAME,
                'reference' => $foreign->REFERENCED_COLUMN_NAME,
                'type' => 'belongsTo',
            ];

            $this->tables[$reference]['relations'][] = [
                'name' => $table,
                'label' => Str::ucfirst($table),
                'column' => $foreign->REFERENCED_COLUMN_NAME,
                'reference' => $foreign->COLUMN_NAME,
                'type' => 'hasMany',
            ];
        }
    }

    /**
     * Add relations with many tot many
     * TODO implement this method if we want?
     * @param Database $database
     * @param string $table
     * @param ConnectionInterface $connection
     */
    private function addRelationsWithManyToMany(Database $database, string $table, ConnectionInterface $connection)
    {
        $foreigns = $connection->select(sprintf('SELECT *
                FROM information_schema.KEY_COLUMN_USAGE
                where TABLE_NAME = "%s"
                AND TABLE_SCHEMA = "%s"', $table, $database->name));

        $references = [$table];
        foreach ($foreigns as $foreign) {
            if (!$foreign->REFERENCED_TABLE_NAME) {
                continue;
            }

            $references[] = $foreign->REFERENCED_TABLE_NAME;
        }

        foreach ($foreigns as $foreign) {
            if (!$foreign->REFERENCED_TABLE_NAME) {
                continue;
            }
            foreach ($references as $reference) {
                if ($foreign->REFERENCED_TABLE_NAME === $reference) {
                    $this->tables[$reference]['relations'][] = [
                        'name' => $foreign->TABLE_NAME,
                        'label' => Str::ucfirst($foreign->TABLE_NAME),
                        'column' => $foreign->REFERENCED_COLUMN_NAME,
                        'reference' => $foreign->COLUMN_NAME,
                    ];
                    continue;
                }
                $this->tables[$reference]['relations'][] = [
                    'name' => $foreign->REFERENCED_TABLE_NAME,
                    'label' => Str::ucfirst($foreign->REFERENCED_TABLE_NAME),
                    'column' => $foreign->COLUMN_NAME,
                    'reference' => $foreign->REFERENCED_COLUMN_NAME,
                ];
            }
        }
    }
}
