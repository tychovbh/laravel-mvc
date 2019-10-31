<?php

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * The users
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
