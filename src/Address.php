<?php

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    /**
     * Address constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillable(['zipcode', 'street', 'city', 'house_number', 'country_id']);
        $this->columns(['zipcode', 'street', 'city', 'house_number', 'country_id']);
        $this->associations([
            [
            'relation' => 'country',
            'model' => Country::class,
            'post_field' => 'country',
            'table_field' => 'name',
            'type' => BelongsTo::class
]
        ]);
        parent::__construct($attributes);
    }

    /**
     * The Countries
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
