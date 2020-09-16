<?php

namespace Tychovbh\Mvc;

class Product extends Model
{
    /**
     * Product constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('label', 'name', 'days', 'renew', 'price', 'tax_rate', 'tax_incl', 'options');
        parent::__construct($attributes);
    }
}
