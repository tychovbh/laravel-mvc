<?php

namespace Tychovbh\Mvc;

class Property extends Model
{
    /**
     * Property constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('name', 'label', 'options');
        $this->casts(['options' => 'array']);

        parent::__construct($attributes);
    }
}
