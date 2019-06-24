<?php

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = ['label', 'name', 'description', 'table'];
}
