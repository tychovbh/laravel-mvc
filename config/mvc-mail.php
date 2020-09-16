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
                'subject' => 'You\'re invited to ' . config('app.name')
            ]
        ],
        'user' => [
            'store' => [
                'template' => 'emails.user.store',
                'subject' => 'New user registered ' . config('app.name'),
                'to' => 'users@domain.com',
                'from' => 'noreply@domain.com',
            ],
            'verify' => [
                'template' => 'emails.user.verify',
                'subject' => 'Verify your email for ' . config('app.name'),
                'from' => 'noreply@domain.com',
            ],
            'password_reset' => [
                'template' => 'emails.user.password_reset',
                'subject' => 'Your Password Reset Link for ' . config('app.name'),
                'from' => 'noreply@domain.com',
            ]
        ],
        'payment' => [
            'paid' => [
                'template' => 'emails.payment.paid',
                'subject' => 'Payment received',
                'from' => 'noreply@domain.com',
                'enabled' => true,
            ],
            'expired' => [
                'template' => 'emails.payment.expired',
                'subject' => 'Payment expired',
                'from' => 'noreply@domain.com',
                'enabled' => true,
            ],
            'cancelled' => [
                'template' => 'emails.payment.cancelled',
                'subject' => 'Payment cancelled',
                'from' => 'noreply@domain.com',
                'enabled' => true,
            ],
            'failed' => [
                'template' => 'emails.payment.failed',
                'subject' => 'Payment failed',
                'from' => 'noreply@domain.com',
                'enabled' => true,
            ],
        ]
    ]
];
