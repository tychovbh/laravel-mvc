<?php
namespace Tychovbh\Mvc\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tychovbh\Mvc\Models\Role;
use Tychovbh\Mvc\Models\UserRole;

trait HasRoles
{
    /**
     * @var bool
     */
    public $hasRoles = true;

    /**
     * Initialize the soft deleting trait for an instance.
     *
     * @return void
     */
    public function initializeHasRoles()
    {
        $this->associations([
            [
                'relation' => 'roles',
                'model' => Role::class,
                'post_field' => 'role_id',
                'table_field' => 'id',
                'type' => BelongsToMany::class,
            ],
            [
                'relation' => 'roles',
                'model' => Role::class,
                'post_field' => 'role',
                'table_field' => 'name',
                'type' => BelongsToMany::class,
            ],
        ]);
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

}
