<?php

return [
    'default' => env('VOUCHER_SERVICE', 'WinstUitJeWoning'),
    'providers' => [
        'WinstUitJeWoning' => [
            'url' => env('WINSUITJEWONING_URL', ''),
            'token' => env('WINSUITJEWONING_TOKEN', ''),
            'store' => [
                'name' => env('WINSUITJEWONING_STORE', ''),
                'id' => env('WINSUITJEWONING_STORE_ID', '')
            ],
        ]
    ]
];
