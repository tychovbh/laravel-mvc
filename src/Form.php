<?php

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    protected $fillable = ['label', 'name', 'description', 'table', 'route'];

    /**
     * The Fields
     * @return HasMany
     */
    public function fields(): HasMany
    {
        return $this->hasMany(Field::class);
    }
}
