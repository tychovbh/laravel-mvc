<?php

namespace Tychovbh\Mvc;

class TokenType extends Model
{
    const INVITE_USER = 'invite_user';
    const VERIFY_EMAIL = 'verify_email';

    /**
     * @var array
     */
    protected $fillable = ['name', 'label'];
}
