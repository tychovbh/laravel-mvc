<?php

namespace Tychovbh\Mvc\Models;

use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    public function __construct(array $attributes = [])
    {
        $this->fillable = ['table', 'relation_id', 'data'];
        parent::__construct($attributes);
    }
}
