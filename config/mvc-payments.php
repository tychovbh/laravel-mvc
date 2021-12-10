<?php
declare(strict_types=1);

use Tychovbh\Mvc\Events\PaymentUpdated;

return [
    'enabled' => env('PAYMENTS_ENABLED', false),
    'return' => env('PAYMENTS_RETURN', 'http://localhost:3000/payments/{id}'),
    'broadcasting' => [
        'enabled' => env('PAYMENTS_BROADCASTING_ENABLED', false),
        'event' => PaymentUpdated::class,
    ]
];
