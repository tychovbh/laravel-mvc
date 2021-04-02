<?php

namespace Tychovbh\Mvc\Observers;

use App\Repositories\RevisionRepository;
use Illuminate\Support\Facades\Cache;
use Tychovbh\Mvc\Models\Model;
use Tychovbh\Mvc\Models\Revision;

class ModelObserver
{
    protected $revision = [];

    /**
     * Created event.
     * @param Model $model
     */
    public function created(Model $model)
    {
        $this->flushIndex($model);
    }

    /**
     * Updating event.
     * @param Model $model
     */
    public function updating(Model $model)
    {
        $this->revision = [
            'table' => $model->getTable(),
            'relation_id' => $model->id,
            'data' => json_encode($model)
        ];
    }

    /**
     * Updated event.
     * @param Model $model
     */
    public function updated(Model $model)
    {
        $revision = new Revision();
        $revision->fill($this->revision);
        $revision->save();

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
