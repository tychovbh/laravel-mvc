<?php

namespace Tychovbh\Mvc;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Tychovbh\Mvc\Repositories\TokenRepository;

class User extends Model
{
    /**
     * User constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('name', 'email', 'password', 'reference', 'avatar', 'is_admin', 'role_id');
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
     * Add user events
     */
    protected static function boot()
    {
        self::updating(function(User $user) {
            if ($user->reference) {
                $tokens = new TokenRepository();
                $token = $tokens->findBy('reference', $user->reference);
                $user->verify($token);
                $tokens->destroy([$token->id]);
            }
        });

        parent::boot();
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

    /**
     * Verify user
     * @param Token $token
     */
    private function verify(Token $token)
    {
        if ($token->type->name !== TokenType::VERIFY_EMAIL) {
            return;
        }

        $data = token_value($token->value);
        if ($this->id === $data['id']) {
            $this->email_verified_at = Carbon::now();
            Arr::forget($this->attributes, 'reference');
        }
    }
}
