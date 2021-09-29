<?php

namespace Tychovbh\Mvc\Models;

use Tychovbh\Mvc\Contracts\HasOptions;

class Product extends Model
{
    use HasOptions;

    /**
     * Product constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('label', 'name', 'days', 'renew', 'price', 'tax_rate', 'tax_incl');
        parent::__construct($attributes);
    }
}
