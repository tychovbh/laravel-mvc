<?php

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Mail;
use Tychovbh\Mvc\Mail\UserInvite;

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
            if ($token->type->name === TokenType::INVITE_USER) {
                $data = token_value($token->value);
                $data['link'] = str_replace('{reference}', $token->reference, config('mvc-auth.url'));
                Mail::send(new UserInvite($data));
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
