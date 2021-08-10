<?php
namespace Tychovbh\Mvc\Contracts;

use Illuminate\Database\Eloquent\Model;
use Tychovbh\Mvc\Storage;
use Illuminate\Http\UploadedFile;

trait HasFiles
{
    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootHasFiles()
    {
        static::saving(function (Model $model) {
            $model->storeFiles();
        });

        static::deleting(function (Model $model) {
            $model->deleteFiles();
        });
    }

    /**
     * Initialize the has files trait for an instance.
     *
     * @return void
     */
    public function initializeSoftDeletes()
    {
        foreach ($this->files as $name => $path) {
            $this->fillables[] = $name;
        }
    }

    /**
     * Store files
     */
    public function storeFiles()
    {
        if (!$this->files) {
            return;
        }

        foreach ($this->files as $name => $path) {
            $file = $this->{$name};
            if (!$file || !is_a($file, UploadedFile::class)) {
                continue;
            }

            if ($this->getOriginal($name)) {
                Storage::delete($this->getOriginal($name));
            }

            $this->{$name} = Storage::store($path, $file);
        }
    }

    /**
     * Delete files
     */
    public function deleteFiles()
    {
        if (!$this->files) {
            return;
        }

        foreach ($this->files as $name => $path) {
            if ($this->{$name}) {
                Storage::delete($this->{$name});
            }
        }
    }
}
