<?php
declare(strict_types=1);

return [
    'headers' => [
//        'company:Bespoke Web'
    ],
    'messages' => [
        'invite' => [
            'store' => [
                'template' => 'emails.invite.store',
                'subject' => 'Your invited to ' . config('app.name')
            ]
        ],
        'user' => [
            'store' => [
                'template' => 'emails.user.store',
                'subject' => 'New user registered ' . config('app.name'),
                'to' => 'users@domain.com',
                'from' => 'noreply@domain.com',
            ],
            'password_reset' => [
                'template' => 'emails.user.password_reset',
                'subject' => 'Your Password Reset Link for ' . config('app.name'),
                'from' => 'noreply@domain.com',
            ]
        ]
    ]
];
