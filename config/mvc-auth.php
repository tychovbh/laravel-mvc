<?php

declare(strict_types=1);

return [
    'url' => env('AUTH_URL'),
    'password_reset_url' => env('AUTH_PASSWORD_RESET_URL'),
    'email_verify_url' => env('AUTH_VERIFY_EMAIL_URL'),
    'email_verify_enabled' => env('AUTH_EMAIL_VERIFY_ENABLED', false),
    'secret' => env('AUTH_SECRET'),
    'id' => env('AUTH_ID'),
    'expiration' => (int) (env('AUTH_EXPIRATION') ?? 3600),
    'login_field' => env('AUTH_LOGIN_FIELD', 'email'),
    'third_party_authentication' => [
        'url' => env('AUTH_THIRD_PARTY_AUTHENTICATION_URL')
    ]
];
