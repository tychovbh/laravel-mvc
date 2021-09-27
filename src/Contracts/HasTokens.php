<?php
namespace Tychovbh\Mvc\Contracts;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Tychovbh\Mvc\Models\Token;
use Tychovbh\Mvc\Models\TokenType;
use Tychovbh\Mvc\Models\User;
use Tychovbh\Mvc\Repositories\TokenRepository;

trait HasTokens
{
    /**
     * Boot Has Tokens
     *
     * @return void
     */
    public static function bootHasTokens()
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
    }

    /**
     * Verify user
     * @param Token $token
     */
    public function verify(Token $token)
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
