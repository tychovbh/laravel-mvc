<?php
namespace Tychovbh\Mvc\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

trait HasCache
{
    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootHasCache()
    {
        static::created(function (Model $model) {
            $model->clearIndex($model);
        });

        static::updated(function (Model $model) {
            $model->clearShow($model);
            $model->clearIndex($model);
        });

        static::deleted(function (Model $model) {
            $model->clearShow($model);
            $model->clearIndex($model);
        });
    }

//    public static function find($id)
//    {
//        return Cache::tags([self::modelTag()])
//            ->remember($token, now()->addHour(), function () use ($endpoint, $token, $token_key) {
//                return $this->authenticate($endpoint, $token, $token_key);
//            });
//    }

    /**
     * The model cache tag
     * @param string $table
     * @param $id
     * @return string
     */
    public static function modelTag(string $table, $id)
    {
        return sprintf('%s.show.model.%s', $table, '.' . $id);
    }

    /**
     * Clear cache show
     * @param Model $model
     */
    public function clearShow(Model $model)
    {
        Cache::tags([
            sprintf('%s.show.%s', $model->getTable(), '.' . $model->id),
            static::modelTag($model->getTable(), $model->id),
        ])->flush();
    }

    /**
     * Clear cache index
     * @param Model $model
     */
    public function clearIndex(Model $model)
    {
        Cache::tags([
            sprintf('%s.index.%s', $model->getTable(), $model->id),
            sprintf('%s.index.collection', $model->getTable()),
        ])->flush();
    }
}
