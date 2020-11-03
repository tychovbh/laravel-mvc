<?php

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Country extends Model
{
    /**
     * Address constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->columns(['name', 'label']);
        $this->fillable(['name', 'label']);

        parent::__construct($attributes);
    }
}

