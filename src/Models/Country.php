<?php

namespace Tychovbh\Mvc\Models;

class Country extends Model
{
    /**
     * Address constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('name', 'label');
        $this->columns('name', 'label');

        parent::__construct($attributes);
    }
}

