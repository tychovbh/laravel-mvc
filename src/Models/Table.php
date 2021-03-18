<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string label
 * @property string name
 * @property string create_title
 * @property string edit_title
 * @property array fields
 * @property array relations
 * @property int database_id
 * @property Database database
 * @property Collection form_fields
 * @property array create_form
 * @property array edit_form
 * @property Collection index_fields
 * @property Collection show_fields
 */
class Table extends Model
{
    use HasFactory;

    const INPUT_TYPES = [
        'bigint' => 'number',
        'int' => 'number',
        'varchar' => 'text',
        'date' => 'date',
    ];

    /**
     * Element constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('label', 'name', 'create_title', 'edit_title', 'fields', 'relations', 'database_id');
        $this->columns('name', 'database_id');
        $this->casts(['fields' => 'array', 'relations' => 'array']);
        parent::__construct($attributes);
    }

    /**
     * The Database
     * @return BelongsTo
     */
    public function database(): BelongsTo
    {
        return $this->belongsTo(Database::class);
    }

    /**
     * The column element
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

    /**
     * The column is editable
     * @param bool $auto_increment
     * @param string $column_type
     * @return bool
     */
    public static function editable(bool $auto_increment, string $column_type): bool
    {
        if ($auto_increment) {
            return false;
        }

        if ($column_type === 'timestamp') {
            return false;
        }

        return true;
    }


    /**
     * The column input properties
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
     * The column input select
     * @param string $name
     * @param bool $is_nullable
     * @return array
     */
    public static function inputSelect(string $name, bool $is_nullable): array
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

    /**
     * The Form Fields
     * @return Collection
     */
    public function getFormFieldsAttribute(): Collection
    {
        return collect($this->fields ?? [])
            ->filter(function ($field) {
                return $field['fillable'];
            })
            ->map(function($field) {
                return [
                    'element' => ['name' => $field['element']],
                    'properties' => $field['properties']
                ];
            });
    }

    /**
     * The Create Form.
     * @return array
     */
    public function getCreateFormAttribute(): array
    {
        return [
            'name' => Str::slug($this->create_title),
            'title' => $this->create_title,
            'route' => route('wildcards.store', [
                'connection' => $this->database->name,
                'name' => $this->name,
            ]),
            'fields' => $this->form_fields->toArray()
        ];
    }

    /**
     * The Edit Form.
     * @return array
     */
    public function getEditFormAttribute(): array
    {
        return [
            'name' => Str::slug($this->edit_title),
            'title' => $this->edit_title,
            'route' => route('wildcards.edit', [
                'connection' => $this->database->name,
                'name' => $this->name,
                'id' => 'id',
            ]),
            'fields' => $this->form_fields->toArray()
        ];
    }

    /**
     * The Index Fields
     * @return Collection
     */
    public function getIndexFieldsAttribute(): Collection
    {
        return collect($this->fields ?? [])
            ->filter(function ($field) {
                return $field['index'];
            })
            ->map(function($field) {
                return [
                    'label' => $field['label'],
                    'name' => $field['name'],
                ];
            });
    }

    /**
     * The Show Fields
     * @return Collection
     */
    public function getShowFieldsAttribute(): Collection
    {
        return collect($this->fields ?? [])
            ->filter(function ($field) {
                return $field['show'];
            })
            ->map(function($field) {
                return [
                    'label' => $field['label'],
                    'name' => $field['name'],
                ];
            });
    }
}
