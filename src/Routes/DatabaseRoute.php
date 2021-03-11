<?php

declare(strict_types=1);

namespace Tychovbh\Mvc\Routes;

class DatabaseRoute extends AbstractRoutes implements Routes
{
    /**
     * @param array $options
     */
    public static function routes(array $options = [])
    {
        $instance = self::instance();
        $instance->index('databases', $options);
        $instance->show('databases', $options);
        $instance->store('databases', $options);
        $instance->update('databases', $options);
        $instance->destroy('databases', $options);
    }
}
