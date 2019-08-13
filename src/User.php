<?php

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    /**
     * User constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('name', 'email', 'password', 'email_verified_at', 'avatar', 'is_admin', 'role_id');
        $this->hiddens('password');
        $this->associations([
            'roles' => [
                'model' => Role::class,
                'post_field' => 'role_id',
                'table_field' => 'id',
                'type' => BelongsToMany::class,
            ]
        ]);
        parent::__construct($attributes);
    }

    /**
     * The roles.
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * The user roles.
     * @return HasMany
     */
    public function user_roles(): HasMany
    {
        return $this->hasMany(UserRole::class);
    }

    /**
     * Hash password.
     * @param string $password
     */
    public function setPasswordAttribute(string $password)
    {
        $this->attributes['password'] = Hash::make($password);
    }
}
