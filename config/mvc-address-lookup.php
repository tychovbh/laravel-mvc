<?php

return [
    'default' => env('ADDRESS_LOOKUP_SERVICE', 'PdokService'),
    'providers' => [
        'PdokService' => [
            'base_url' => env('PDOKSERVICE_BASE_URL', '')
        ]
    ]
];
