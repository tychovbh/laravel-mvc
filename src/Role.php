<?php

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    /**
     * Role constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('name', 'label', 'users');
        $this->associations([
            'users' => [
                'model' => User::class,
                'post_field' => 'users',
                'table_field' => 'id',
                'type' => BelongsToMany::class,
            ]
        ]);
        parent::__construct($attributes);
    }

    /**
     * The users
     * @return belongsToMany
     */
    public function users(): belongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }
}
