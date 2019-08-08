<?php
declare(strict_types=1);

return [
    'headers' => [
//        'company:bespoke web'
    ],
    'templates' => [
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
            ]
        ]
    ]
];
