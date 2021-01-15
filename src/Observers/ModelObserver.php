<?php

namespace Tychovbh\Mvc\Observers;

use Illuminate\Support\Facades\Cache;
use Tychovbh\Mvc\Model;

class ModelObserver
{
    /**
     * Created event.
     * @param Model $model
     */
    public function created(Model $model)
    {
        $this->flushIndex($model);
    }

    /**
     * Updated event.
     * @param Model $model
     */
    public function updated(Model $model)
    {
        $this->flushIndex($model);
        $this->flushShow($model);
    }

    /**
     * Deleted event.
     * @param Model $model
     */
    public function deleted(Model $model)
    {
        $this->flushIndex($model);
        $this->flushShow($model);
    }

    /**
     * Flush Index.
     * @param Model $model
     */
    private function flushIndex(Model $model)
    {
        if ($model->cacheable()) {
            Cache::tags($model->getTable() . '.index')->flush();
        }
    }

    /**
     * Flush show.
     * @param Model $model
     */
    private function flushShow(Model $model)
    {
        if ($model->cacheable()) {
            Cache::tags($model->getTable() . '.show.' . $model->id)->flush();
        }
    }
}
