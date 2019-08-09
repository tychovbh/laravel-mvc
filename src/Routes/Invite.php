<?php

declare(strict_types=1);

namespace Tychovbh\Mvc\Routes;

class Invite extends AbstractRoutes implements Routes
{
    /**
     * @param array $options
     */
    public static function routes(array $options = [])
    {
        $instance = self::instance();
        $instance->store('invites', $options, ['validate']);
    }
}
