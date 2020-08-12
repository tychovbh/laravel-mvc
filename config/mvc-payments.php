<?php
declare(strict_types=1);

use Tychovbh\Mvc\Events\PaymentUpdated;

return [
    'return' => env('PAYMENTS_RETURN', 'http://localhost:3000/{id}'),
    'broadcasting' => [
        'enabled' => env('PAYMENTS_BROADCASTING_ENABLED', false),
        'event' => PaymentUpdated::class,
    ]
];
