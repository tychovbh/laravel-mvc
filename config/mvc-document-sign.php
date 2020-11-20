<?php

return [
    'default' => env('DOCUMENT_SIGN_PROVIDER', 'SignRequest'),
    'providers' => [
        'SignRequest' => [
            'token' => '',
            'subdomain' => '',
        ]
    ]
];
