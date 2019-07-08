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

    protected $saveAssociations = [];

    /**
     * Return files
     * @return array
     */
    public function files()
    {
        return $this->files;
    }

    /**
     * Return associations
     * @return array
     */
    public function associations()
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

        $this->saveAssociation([], $options);
        return parent::save($options);
    }

    /**
     * Save association
     * @param array $associations
     * @param array $options
     */
    public function saveAssociation(array $associations, array $options = [])
    {
        foreach ($this->associations() as $association) {
            if (Arr::has($this->attributes, $association['post_field'])) {
                $type = 'save' . str_replace('Illuminate\\Database\\Eloquent\\Relations\\', '', $association['type']);
                $relations = $this->attributes[$association['post_field']];
                Arr::forget($this->attributes, $association['post_field']);
                $this->{$type}($association, $relations, $options);
            }
        }
    }

    private function saveBelongsTo(array $association, string $relation, array $options = [])
    {
        $this->attributes[$association['post_field'] . '_id'] = $association['model']::where(
            $association['table_field'],
            $relation
        )
            ->firstOrFail()
            ->id;
    }

    private function saveBelongsToMany(array $association, array $relations, array $options = [])
    {
        foreach ($relations as $value) {
            $model = $association['model']::where($association['table_field'], $value)->firstOrFail();
            $this->{$association['post_field']}()->save($model);
        }
    }

    private function saveHasMany(array $association, array $relations, array $options = [])
    {
        parent::save($options);
        $this->{$association['post_field']}()->createMany($relations);
//        foreach ($associations[$association['post_field']] as $value) {
//            $this->{$association['post_field']}()->save(new $association['model']($value));
//        }
    }

    /**
     * Save files
     */
    private function saveFiles()
    {
        foreach ($this->files() as $name => $path) {
            if (Arr::has($this->attributes, $name) && is_a($this->attributes[$name], UploadedFile::class)) {
                $path_new = $this->{$name}->store($path);
                $this->attributes[$name] = str_replace('public/', '', $path_new);
                if (Arr::has($this->original, $name)) {
                    Storage::delete($this->original[$name]);
                }
            }
        }
    }
}
