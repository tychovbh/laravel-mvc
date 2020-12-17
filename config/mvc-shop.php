<?php

return [
    'default' => env('SHOP_SERVICE', 'Shopify'),
    'providers' => [
        'Shopify' => [
            'api_key' => env('SHOPIFY_API_KEY', ''),
            'password' => env('SHOPIFY_PASSWORD', ''),
            'domain' => env('SHOPIFY_DOMAIN', ''),
            'version' => env('SHOPIFY_VERSION', ''),
        ]
    ]
];
