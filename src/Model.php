<?php

namespace Tychovbh\Mvc;

use Exception;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class Model extends BaseModel
{
    /**
     * Model constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        foreach ($this->associations as $association) {
            $this->fillables($association['post_field']);
        }
        $this->casts(['options' => 'array']);
        parent::__construct($attributes);
    }

    /**
     * @var array
     */
    protected $files = [];

    /**
     * @var array
     */
    protected $associations = [];

    /**
     * @var array
     */
    protected $unique = [];

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * Return files
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Return associations
     * @return array
     */
    public function getAssociations()
    {
        return $this->associations;
    }

    /**
     * @param string $name
     * @param array $association
     */
    public function modifyAssociations(string $name, array $association)
    {
        $this->associations[$name] = array_merge($this->associations[$name], $association);
    }

    /**
     * Save the model
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $this->saveFiles();

        $this->saveAssociations($options);

        if ($this->unique) {
            $this->updateIfNotUnique();
        }

        return parent::save($options);
    }

    /**
     * Save associations
     * @param array $options
     */
    protected function saveAssociations(array $options = [])
    {
        $associations = [];
        foreach ($this->getAssociations() as $association) {
            if (Arr::has($this->attributes, $association['post_field'])) {
                $type = 'save' . str_replace('Illuminate\\Database\\Eloquent\\Relations\\', '', $association['type']);
                $values = $this->attributes[$association['post_field']];

                if (Arr::has($association, 'pivots')) {
                    $association['pivots'] = $this->pivots($association['pivots']);
                }

                $associations[$association['relation'] . '.' .  $association['post_field']] = [
                    'type' => $type,
                    'association' => $association,
                    'relation' => $association['relation'],
                    'values' => $values,
                    'options' => $options,
                ];

                Arr::forget($this->attributes, $association['post_field']);
            }
        }

        foreach ($associations as $association) {
            if ($association['values']) {
                $this->{$association['type']}(
                    $association['association'],
                    $association['relation'],
                    $association['values'],
                    $association['options']
                );
            }
        }
    }

    /**
     * Save belongs to relation
     * @param array $association
     * @param string $relation
     * @param string $value
     * @param array $options
     */
    protected function saveBelongsTo(array $association, string $relation, string $value, array $options = [])
    {
        $this->attributes[$relation . '_id'] = $association['model']::where(
            $association['table_field'],
            $value
        )
            ->firstOrFail()
            ->id;
    }

    /**
     * Save belongs to many relation
     * TODO expand method to save new relations
     * @param array $association
     * @param string $relation
     * @param mixed $values
     * @param array $options
     */
    protected function saveBelongsToMany(array $association, string $relation, $values, array $options = [])
    {
        parent::save($options);

        if (!is_array($values)) {
            $values = [$values];
        }

        foreach ($values as $value) {
            try {
                $model = $association['model']::where($association['table_field'], $value)->firstOrFail();
                $this->{$relation}()->save($model, Arr::get($association, 'pivots', []));
            } catch (Exception $exception) {
                continue;
            }
        }
    }

    /**
     * Save has one relation
     * @param array $association
     * @param string $relation
     * @param $value
     * @param array $options
     */
    protected function saveHasOne(array $association, string $relation, $value, array $options = [])
    {
        parent::save($options);

        $search = is_array($value) ? Arr::get($value, $association['table_field']) : $value;
        $model = $association['model']::where($association['table_field'], $search)->first();
        $model = $model ? $model->fill($value) : new $association['model']($value);

        $this->{$relation}()->save($model);
    }

    /**
     * Get relation pivots
     * @param array $pivots
     * @return array
     */
    private function pivots(array $pivots = []): array
    {
        $values = [];
        foreach ($pivots as $name) {
            if (Arr::has($this->attributes, $name)) {
                $values[$name] = $this->getAttribute($name);
                Arr::forget($this->attributes, $name);
            }
        }

        return $values;
    }

    /**
     * Save has many relation
     * @param array $association
     * @param string $relation
     * @param mixed $values
     * @param array $options
     */
    protected function saveHasMany(array $association, string $relation, $values, array $options = [])
    {
        parent::save($options);
        if (!is_array($values)) {
            $values = [$values];
        }
        $this->{$relation}()->createMany($values);
    }

    /**
     * Save files
     */
    protected function saveFiles()
    {
        foreach ($this->getFiles() as $name => $path) {
            if (Arr::has($this->attributes, $name) && is_a($this->attributes[$name], UploadedFile::class)) {
                $path_new = $this->{$name}->store($path);
                $this->attributes[$name] = str_replace('public/', '', $path_new);
                if (Arr::has($this->original, $name)) {
                    Storage::delete($this->original[$name]);
                }
            }
        }
    }

    /**
     * Update model if not Unique (this is commonly used for saving hasMany relations.
     */
    protected function updateIfNotUnique()
    {
        $query = self::query();

        foreach ($this->unique as $unique) {
            $query->where($unique, $this->attributes[$unique]);
        }

        $model = $query->first();

        if ($model) {
            $this->id = $model->id;
            $this->exists = true;
        }
    }

    /**
     * Add fillables
     * @param mixed ...$fillables
     */
    public function fillables(...$fillables)
    {
        $this->fillable = array_merge($this->fillable, $fillables);
    }

    /**
     * Add files
     * @param mixed ...$files
     */
    public function files(...$files)
    {
        foreach ($files as $file) {
            foreach ($file as $name => $path) {
                $this->files[$name] = $path;
            }
        }
    }

    /**
     * Add Hiddens
     * @param array ...$hiddens
     */
    public function hiddens(...$hiddens)
    {
        $this->hidden = array_merge($this->hidden, $hiddens);
    }

    /**
     * Add Associations
     * @param array $associations
     */
    public function associations(array $associations)
    {
        $this->associations = array_merge($this->associations, $associations);
    }

    /**
     * Add Associations
     * @param array $casts
     */
    public function casts(array $casts)
    {
        $this->casts = array_merge($this->casts, $casts);
    }

    /**
     * Add Uniques
     * @param mixed ...$uniques
     */
    public function uniques(...$uniques)
    {
        $this->unique = array_merge($this->unique, $uniques);
    }


    /**
     * Get value from Options
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function option(string $key, $default = null)
    {
        return Arr::get($this->options ?? [], $key, $default);
    }

    /**
     * Check if database has column
     * @param string $key
     * @return bool
     */
    public function hasColumn(string $key): bool
    {
        if (empty($this->columns)) {
            return Schema::hasColumn($this->getTable(), $key);
        }

        return Arr::has($this->columns, $key);
    }
}
