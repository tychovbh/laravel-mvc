<?php

namespace Tychovbh\Mvc;

class TokenType extends Model
{
    const INVITE_USER = 'invite_user';
    const VERIFY_EMAIL = 'verify_email';
    const PASSWORD_RESET = 'password_reset';
    const API_KEY = 'api_key';
    const USER_TOKEN = 'user_token';

    /**
     * @var array
     */
    protected $fillable = ['name', 'label'];
}
