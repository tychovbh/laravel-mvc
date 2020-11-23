<?php

return [
    'default' => env('DOCUMENT_SIGN_PROVIDER', 'SignRequest'),
    'providers' => [
        'SignRequest' => [
            'token' => env('DOCUMENT_SIGN_PROVIDER_TOKEN', ''),
            'subdomain' => env('DOCUMENT_SIGN_PROVIDER_SUBDOMAIN', ''),
        ]
    ]
];
