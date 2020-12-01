<?php

namespace Tychovbh\Mvc\Models;

class Property extends Model
{
    /**
     * Property constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('name', 'label', 'options');
        parent::__construct($attributes);
    }
}
