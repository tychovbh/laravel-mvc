<?php

namespace Tychovbh\Mvc\Models;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Tychovbh\Mvc\Repositories\TokenRepository;

/**
 * Class User
 * @property int id
 * @package Tychovbh\Mvc\Models
 */
class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use HasFactory, Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;

    /**
     * User constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('name', 'email', 'password', 'token', 'avatar', 'is_admin');
        $this->hiddens('password', 'remember_token');

        $this->casts([
            'email_verified_at' => 'datetime',
        ]);

        parent::__construct($attributes);
    }

    /**
     * Hash password.
     * @param string $password
     */
    public function setPasswordAttribute(string $password)
    {
        if ($password !== '') {
            $this->attributes['password'] = Hash::make($password);
        }
    }
}
