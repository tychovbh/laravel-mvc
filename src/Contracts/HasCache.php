<?php
namespace Eyecons\LaravelTools\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * Trait HasCache
 * @package Eyecons\LaravelTools\Contracts
 * @property int id
 * @property string table
 * @property Carbon updated_at
 */
trait HasCache
{
    /**
     * Boot the has cache trait for a model.
     */
    public static function bootHasCache()
    {
        static::saved(function (Model $model) {
            $model->cacheClearSaved();
        });

        static::deleted(function (Model $model) {
            $model->cacheClear();
        });
    }

    /**
     * Clear cache
     */
    public function cacheClearSaved()
    {
        if (
            $this->getOriginal('updated_at') &&
            $this->getOriginal('updated_at')->equalTo($this->updated_at)
        ) {
            return;
        }

        $this->cacheClear();
    }

    public function cacheClear()
    {
        $tags = [
            $this->getTable() . '.show.' . $this->id,
            $this->getTable() . '.index',
        ];

        $tags = array_merge($tags, $this->cacheTags());

        Cache::tags($tags)->flush();
    }

    /**
     * Extra cache tags to be cleared.
     * @return array
     */
    public function cacheTags(): array
    {
        return [];
    }
}
