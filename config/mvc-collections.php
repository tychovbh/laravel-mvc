<?php

declare(strict_types=1);

use Tychovbh\Mvc\TokenType;

return [
    [
        'table' => 'token_types',
        'update_by' => 'name',
        'items' => [
            [
                'name' => TokenType::INVITE_USER,
                'label' => 'Invite User'
            ],
            [
                'name' => TokenType::VERIFY_EMAIL,
                'label' => 'Verify Email'
            ],
            [
                'name' => TokenType::PASSWORD_RESET,
                'label' => 'Password Reset'
            ],
        ]
    ],
    [
        'table' => 'countries',
        'update_by' => 'name',
        'items' => [
            [
                'name' => 'nl',
                'label' => 'Netherlands'
            ]
        ]
    ]
];
