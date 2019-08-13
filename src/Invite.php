<?php

namespace Tychovbh\Mvc;

class Invite extends Model
{
    /**
     * Invite constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('reference', 'token');
        parent::__construct($attributes);
    }
}
