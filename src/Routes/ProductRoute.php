<?php

declare(strict_types=1);

namespace Tychovbh\Mvc\Routes;

class ProductRoute extends AbstractRoutes implements Routes
{
    /**
     * @param array $options
     */
    public static function routes(array $options = [])
    {
        $instance = self::instance();
        $instance->index('products', $options);
        $instance->show('products', $options);
        $instance->store('products', $options);
        $instance->update('products', $options);
        $instance->destroy('products', $options);
    }
}
