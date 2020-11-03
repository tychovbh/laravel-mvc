<?php

declare(strict_types=1);

namespace Tychovbh\Mvc\Routes;

class CountryRoute extends AbstractRoutes implements Routes
{
    /**
     * @param array $options
     */
    public static function routes(array $options = [])
    {
        $instance = self::instance();
        $instance->index('countries', $options);
        $instance->show('countries', $options);
        $instance->store('countries', $options);
        $instance->update('countries', $options);
        $instance->destroy('countries', $options);
    }
}
