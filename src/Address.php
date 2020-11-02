<?php

namespace Tychovbh\Mvc;

class Address extends Model
{
    /**
     * @var array
     */
    protected $columns = ['zipcode', 'street', 'city', 'house_number', 'addition', 'country'];

    /**
     * Address constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillable(['zipcode', 'street', 'city', 'house_number', 'addition', 'country']);
        parent::__construct($attributes);
    }
}
