<?php

return [
    'default' => env('VOUCHER_VALIDATION_SERVICE', 'WinstUitJeWoning'),
    'providers' => [
        'WinstUitJeWoning' => [
            'url' => env('WINSUITJEWONING_URL', '')
        ]
    ]
];
