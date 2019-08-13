<?php
declare(strict_types=1);

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Element extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['label', 'name', 'description', 'properties'];

    /**
     * @var array
     */
    protected $associations = [
        'properties' => [
            'model' => Property::class,
            'post_field' => 'properties',
            'table_field' => 'name',
            'type' => BelongsToMany::class
        ]
    ];

    /**
     * @return BelongsToMany
     */
    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'element_properties');
    }
}
