<?php

declare(strict_types=1);

return [
    'url' => env('AUTH_URL'),
    'password_reset_url' => env('PASSWORD_RESET_URL'),
    'secret' => env('AUTH_SECRET'),
    'id' => env('AUTH_ID'),
    'expiration' => (int) (env('AUTH_EXPIRATION') ?? 3600),
    'login_field' => env('AUTH_LOGIN_FIELD', 'email')
];
