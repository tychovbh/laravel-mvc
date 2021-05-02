<?php
declare(strict_types=1);

return [
    'model' => [
        'notfound' => '%s not found with %s: %s',
        'invalid' => sprintf('The request is invalid! Please contact %s.', config('app.support.email')),
    ],
    'field' => [
        'required' => 'Input field %s is missing from the Request.',
        'required_with' => 'Input field %s is missing from the Request.',
        'required_without' => 'Input field %s is missing from the Request.',
        'min' => 'Input field %s should have a minimum value of %s.',
        'email' => 'E-mailadres is niet geldig.',
        'exists' => '%s with given ID does not exists.',
        'unique' => '%s already taken.',
        'integer' => '%s is not a valid Number.',
        'string' => '%s is not a valid Text.',
        'date' => '%s is not a valid date.',
    ],
    'server' => [
        'error' => sprintf('Server error! Please contact %s.', config('app.support.email')),
    ],
    'security' => [
        'unauthorized' => 'Unauthorized!',
    ],
    'auth' => [
        'token' => [
            'missing' => 'Token is missing in authorization header.',
            'expired' => 'Token is expired.',
            'invalid' => 'Token is invalid.'
        ],
        'third-party-authorization' => [
            'missing' => 'Authorization header is missing',
            'invalid' => 'Authorization header invalid'
        ],
        'notfound' => 'User not found.',
        'unauthorized' => 'Unauthorized!',
    ],
    'login' => [
        'notfound' => 'User not found, please register.',
        'password' => [
            'incorrect' => 'Combination of email and password is incorrect.'
        ],
        'email' => [
            'unverified' => 'Email unverified, please check your mailbox.'
        ],
        'attempts' => 'To many attempts, wait 1 minute and try again.'
    ],
];
