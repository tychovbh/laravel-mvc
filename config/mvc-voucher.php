<?php

return [
    'default' => env('VOUCHER_SERVICE', 'WinstUitJeWoning'),
    'enabled' => env('VOUCHER_SERVICE_ENABLED', 'false'),
    'providers' => [
        'WinstUitJeWoning' => [
            'url' => env('WINSUITJEWONING_URL', ''),
            'token' => env('WINSUITJEWONING_TOKEN', ''),
        ]
    ]
];
