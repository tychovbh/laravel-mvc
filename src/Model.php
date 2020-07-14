<?php

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class Model extends BaseModel
{
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
        foreach ($this->getAssociations() as $relation => $association) {
            if (Arr::has($this->attributes, $association['post_field'])) {
                $type = 'save' . str_replace('Illuminate\\Database\\Eloquent\\Relations\\', '', $association['type']);
                $values = $this->attributes[$association['post_field']];
                Arr::forget($this->attributes, $association['post_field']);
                $associations[] = [
                    'type' => $type,
                    'association' => $association,
                    'relation' => $relation,
                    'values' => $values,
                    'options' => $options
                ];

            }
        }

        foreach ($associations as $association) {
            if ($association['values']) {
                $this->{$association['type']}($association['association'], $association['relation'], $association['values'], $association['options']);
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
        $pivot = [];
        if (Arr::has($association, 'pivots')) {
            $pivot = $this->pivots($association['pivots']);
        }

        parent::save($options);

        if (!is_array($values)) {
            $values = [$values];
        }

        foreach ($values as $value) {
            try {
                $model = $association['model']::where($association['table_field'], $value)->firstOrFail();
                $this->{$relation}()->save($model, $pivot);
            } catch (\Exception $exception) {
                continue;
            }
        }
    }

    /**
     * Get relation pivots
     * @param array $pivots
     * @return array
     */
    private function pivots(array $pivots = [])
    {
        $pivot = [];
        foreach ($pivots as $name) {
            $pivot[$name] = Arr::get($this->attributes, $name);
            Arr::forget($this->attributes, $name);
        }

        return $pivot;
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
        $this->associations = array_merge($associations, $this->associations);
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
}
