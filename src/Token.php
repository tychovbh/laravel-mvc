<?php

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Mail;
use Tychovbh\Mvc\Mail\UserInvite;
use Tychovbh\Mvc\Mail\UserPasswordReset;

class Token extends Model
{
    /**
     * Invite constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('value', 'reference', 'type');
        $this->associations([
            'type' => [
                'model' => TokenType::class,
                'post_field' => 'type',
                'table_field' => 'name',
                'type' => BelongsTo::class
            ]
        ]);
        parent::__construct($attributes);
    }

    /**
     * Add Token events
     */
    protected static function boot()
    {
        self::created(function(Token $token) {
            $data = token_value($token->value);
            if ($token->type->name === TokenType::INVITE_USER) {
                $data['link'] = str_replace('{reference}', $token->reference, config('mvc-auth.url'));
                Mail::send(new UserInvite($data));
            }

            if ($token->type->name === TokenType::PASSWORD_RESET) {
                $data['link'] = str_replace('{reference}', $token->reference, config('mvc-auth.password_reset_url'));
                Mail::send(new UserPasswordReset($data));
            }
        });

        parent::boot();
    }

    /**
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(TokenType::class, 'type_id');
    }
}
