<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Models;

use Database\Factories\DatabaseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;

class Table extends Model
{
    use HasFactory;

    const INPUT_TYPES = [
        'bigint' => 'number',
        'int' => 'number',
        'varchar' => 'input',
        'timestamp' => 'date'
    ];

    /**
     * Element constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('label', 'name', 'fields', 'relations', 'database_id');
        $this->casts(['fields' => 'array', 'relations' => 'array']);
        parent::__construct($attributes);
    }

    /**
     * @return DatabaseFactory
     */
    public static function newFactory(): DatabaseFactory
    {
        return DatabaseFactory::new();
    }

    /**
     * The element
     * @param string $type
     * @return string
     */
    public static function element(string $type): string
    {
        if (in_array($type, ['timestamp', 'date', 'varchar', 'bigint', 'int', 'float'])) {
            return 'input';
        }

        if ($type === 'enum') {
            return 'select';
        }

        error('Element type not found', [
            'type' => $type
        ]);

        return 'input';
    }

    /*
     * Is editable
     */
    public static function editable(bool $auto_increment = false): bool
    {
        if ($auto_increment) {
            return false;
        }

        return true;
    }


    /**
     * The Input properties
     * @param string $name
     * @param string $type
     * @param bool $is_nullable
     * @return array
     */
    public static function inputProperties(string $name, string $type, bool $is_nullable): array
    {
        if (!Arr::has(self::INPUT_TYPES, $type)) {
            error('Element type not found', [
                'type' => $type
            ]);
        }

        return [
            'name' => $name,
            'type' => Arr::get(self::INPUT_TYPES, $type, 'input'),
            'required' => !$is_nullable,
            'placeholder' => ''
        ];
    }

    /**
     * The Input properties
     * @param string $name
     * @param bool $is_nullable
     * @return array
     */
    public static function inputSelect(string $name, $is_nullable): array
    {
        return [
            'name' => $name,
            'type' => '',
            'required' => !$is_nullable,
            'options' => [],
//            'source' => '',
//            'label_key' => 'label',
//            'value_key' => 'id'
        ];
    }
}
