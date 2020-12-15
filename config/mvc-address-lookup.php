<?php

return [
    'default' => env('ADDRESS_LOOKUP_SERVICE', 'PdokService'),
    'enabled' => env('ADDRESS_LOOKUP_SERVICE_ENABLED', 'false'),
    'providers' => [
        'PdokService' => [
            'base_url' => env('PDOKSERVICE_BASE_URL', '')
        ]
    ]
];
