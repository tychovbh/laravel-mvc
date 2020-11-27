<?php

return [
    'pdf' => [
        'enabled' => env('CONTRACTS_PDF_ENABLED', false)
    ],
    'document_sign' => [
        'enabled' => env('CONTRACTS_DOCUMENT_SIGN_ENABLED', false),
        'return' => env('CONTRACTS_REDIRECT_URL', '')
    ]
];
