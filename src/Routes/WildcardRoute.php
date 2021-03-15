<?php

declare(strict_types=1);

namespace Tychovbh\Mvc\Routes;

class WildcardRoute extends AbstractRoutes implements Routes
{
    /**
     * @param array $options
     */
    public static function routes(array $options = [])
    {
        $instance = self::instance();
        $instance->route('get', 'wildcards.index', 'index', '/{connection}/{table}');
        $instance->route('post', 'wildcards.store', 'store', '/{connection}/{table}');
//        $instance->route('get', 'wildcards.index', 'index', '/{database}/{table}');
//        $instance->show('databases', $options);
//        $instance->store('databases', $options);
//        $instance->update('databases', $options);
//        $instance->destroy('databases', $options);
    }
}
