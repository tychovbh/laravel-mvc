<?php

namespace Tychovbh\Mvc;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Tychovbh\Mvc\Mail\UserPasswordReset;

class PasswordReset extends Model
{
    /**
     * @var array
     */
    public $fillable = ['email'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Invite constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('email', 'token');
        parent::__construct($attributes);
    }

    /**
     *
     */
    public static function boot()
    {
        self::creating(function (PasswordReset $passwordReset) {
            $passwordReset->token = random_string();
            $passwordReset->created_at = Carbon::now();
        });

        self::created(function(PasswordReset $passwordReset) {
            Mail::to($passwordReset->email)->send(new UserPasswordReset([
                'user' => $passwordReset->user,
                'link' => str_replace('{reference}', $passwordReset->token, config('mvc-auth.password_reset_url')),
            ]));
        });

        parent::boot();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @throws \Exception
     */
    public function user()
    {
        return $this->belongsTo(project_or_package_class('Model',User::class), 'email', 'email');
    }
}
