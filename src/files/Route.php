<?php

declare(strict_types=1);

namespace Tychovbh\Mvc\Routes;

class EntityRoute extends AbstractRoutes implements Routes
{
    /**
     * @param array $options
     */
    public static function routes(array $options = [])
    {
        $instance = self::instance();
        $instance->index('{{name}}', $options);
        $instance->show('{{name}}', $options);
        $instance->store('{{name}}', $options);
        $instance->update('{{name}}', $options);
        $instance->destroy('{{name}}', $options);
    }
}
