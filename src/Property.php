<?php

namespace Tychovbh\Mvc;

class Property extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'label', 'options'];

    /**
     * @var array
     */
    protected $casts = ['options' => 'array'];
}
