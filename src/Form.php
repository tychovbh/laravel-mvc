<?php
declare(strict_types=1);

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    /**
     * Form constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('label', 'name', 'description', 'table', 'fields');
        $this->associations([
            'fields' => [
                'model' => Field::class,
                'post_field' => 'fields',
                'type' => HasMany::class
            ]
        ]);
        parent::__construct($attributes);
    }

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
