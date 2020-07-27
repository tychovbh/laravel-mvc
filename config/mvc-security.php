<?php
declare(strict_types=1);

return [
    'whitelist' => explode(',', env('SECURITY_WHITELIST', '')),
    'logging' => env('SECURITY_LOGGING', true)
];
