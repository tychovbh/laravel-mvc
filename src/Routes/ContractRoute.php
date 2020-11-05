<?php

declare(strict_types=1);

namespace Tychovbh\Mvc\Routes;

class ContractRoute extends AbstractRoutes implements Routes
{
    /**
     * @param array $options
     */
    public static function routes(array $options = [])
    {
        $instance = self::instance();
        $instance->index('contracts', $options);
        $instance->show('contracts', $options);
        $instance->store('contracts', $options);
        $instance->update('contracts', $options);
        $instance->destroy('contracts', $options);
    }
}
