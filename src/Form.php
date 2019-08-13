<?php
declare(strict_types=1);

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['label', 'name', 'description', 'table', 'fields'];

    /**
     * @var array
     */
    protected $associations = [
        'fields' => [
            'model' => Field::class,
            'post_field' => 'fields',
            'type' => HasMany::class
        ]
    ];

    /**
     * The Fields
     * @return HasMany
     */
    public function fields(): HasMany
    {
        return $this->hasMany(Field::class);
    }

    /**
     * The route
     * @return string
     */
    public function getRouteAttribute() : string
    {
        try {
            return route($this->name . '.store');
        } catch (\Exception $exception) {
            return '';
        }
    }
}
