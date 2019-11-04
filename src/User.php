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
        $this->fillables('name', 'email', 'password', 'token', 'avatar', 'is_admin', 'role_id');
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
        self::saving(function(User $user) {
            if ($user->token) {
                $tokens = new TokenRepository();
                $token = $tokens->findBy('reference', $user->token);
                $user->verify($token);
                $tokens->destroy([$token->id]);
                Arr::forget($user->attributes, 'token');
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
        if (!in_array($token->type->name, [TokenType::VERIFY_EMAIL, TokenType::INVITE_USER])) {
            return;
        }

        $data = token_value($token->value);
        if ($this->email === $data['email']) {
            $this->email_verified_at = Carbon::now();
        }
    }
}
