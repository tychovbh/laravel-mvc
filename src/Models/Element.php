<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Element extends Model
{
    /**
     * Element constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('label', 'name', 'description', 'properties');
        $this->associations([
            [
                'relation' => 'properties',
                'model' => Property::class,
                'post_field' => 'properties',
                'table_field' => 'name',
                'type' => BelongsToMany::class
            ]
        ]);

        parent::__construct($attributes);
    }

    /**
     * @return BelongsToMany
     */
    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'element_properties');
    }
}
