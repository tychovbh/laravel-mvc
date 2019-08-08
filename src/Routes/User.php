<?php

declare(strict_types=1);

namespace Tychovbh\Mvc\Routes;

use Illuminate\Support\Arr;

class User extends AbstractRoutes implements Routes
{
    /**
     * @param array $options
     */
    public static function routes(array $options = [])
    {
        $options['store']['middleware'] = array_merge(Arr::get($options, 'middleware', []), ['validate']);

        $instance = self::instance();
        $instance->show('users', $options);
        $instance->store('users', $options, ['validate']);
    }
}
