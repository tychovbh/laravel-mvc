<?php

namespace Tychovbh\Mvc;

use App\Team;
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
        foreach ($this->getAssociations() as $association) {
            if (Arr::has($this->attributes, $association['post_field'])) {
                $type = 'save' . str_replace('Illuminate\\Database\\Eloquent\\Relations\\', '', $association['type']);
                $relations = $this->attributes[$association['post_field']];
                Arr::forget($this->attributes, $association['post_field']);
                if ($relations) {
                    $this->{$type}($association, $relations, $options);
                }
            }
        }
    }

    /**
     * Save belongs to relation
     * @param array $association
     * @param string $relation
     * @param array $options
     */
    protected function saveBelongsTo(array $association, string $relation, array $options = [])
    {
        $this->attributes[$association['post_field'] . '_id'] = $association['model']::where(
            $association['table_field'],
            $relation
        )
            ->firstOrFail()
            ->id;
    }

    /**
     * Save belongs to many relation
     * @param array $association
     * @param mixed $relations
     * @param array $options
     */
    protected function saveBelongsToMany(array $association, $relations, array $options = [])
    {
        parent::save($options);
        if (!is_array($relations)) {
            $relations = [$relations];
        }
        foreach ($relations as $value) {
            $model = $association['model']::where($association['table_field'], $value)->firstOrFail();
            $this->{$association['post_field']}()->save($model);
        }
    }

    /**
     * Save has many relation
     * @param array $association
     * @param mixed $relations
     * @param array $options
     */
    protected function saveHasMany(array $association, $relations, array $options = [])
    {
        parent::save($options);
        if (!is_array($relations)) {
            $relations = [$relations];
        }
        $this->{$association['post_field']}()->createMany($relations);
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
     * @param mixed ...$associations
     */
    public function associations(...$associations)
    {
        $this->associations = array_merge($this->associations, $associations);
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
