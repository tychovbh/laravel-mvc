<?php

namespace Tychovbh\Mvc;

class Role extends Model
{
    /**
     * Role constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('name', 'label');

        parent::__construct($attributes);
    }
}
