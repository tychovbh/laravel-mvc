<?php

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Model;

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
