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
        $this->saveAssociation();
        return parent::save($options);
    }

    /**
     * Save association
     */
    public function saveAssociation()
    {
        foreach ($this->associations() as $association) {
            if (Arr::has($this->attributes, $association['post_field'])) {
                $this->attributes[$association['post_field'] . '_id'] = $association['model']::where(
                    $association['table_field'],
                    $this->attributes['input']
                )
                    ->firstOrFail()
                    ->id;
            }
        }
    }

    /**
     * Save files
     */
    private function saveFiles()
    {
        foreach ($this->files() as $name => $path) {
            $test = $this->attributes;
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
