<?php

return [
    'default' => env('ADDRESS_LOOKUP_SERICE', 'PdokService'),
    'providers' => [
        'PdokService' => [
            'base_url' => env('PDOKSERVICE_BASE_URL', '')
        ]
    ]
];
