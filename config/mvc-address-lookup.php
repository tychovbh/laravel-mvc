<?php

return [
    'default' => env('ADDRESS_LOOKUP_SERICE', 'PdokService'),
    'providers' => [
        'PdokService' => [
            'base-url' => ''
        ]
    ]
];
