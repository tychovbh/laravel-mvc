<?php

return [
    'default' => env('DOCUMENT_SIGN_PROVIDER', 'SignRequest'),
    'providers' => [
        'SignRequest' => [
            'token' => env('SIGNREQUEST_TOKEN', ''),
            'subdomain' => env('SIGNREQUEST_SUBDOMAIN', ''),
        ]
    ]
];
