<?php

return [
    'default' => env('VOUCHER_VALIDATION_SERVICE', 'WinstUitJeWoning'),
    'enabled' => env('VOUCHER_VALIDATION_SERVICE_ENABLED', 'false'),
    'providers' => [
        'WinstUitJeWoning' => [
            'url' => env('WINSUITJEWONING_URL', '')
        ]
    ]
];
