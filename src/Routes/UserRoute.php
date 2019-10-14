<?php

declare(strict_types=1);

namespace Tychovbh\Mvc\Routes;

class UserRoute extends AbstractRoutes implements Routes
{
    /**
     * @param array $options
     */
    public static function routes(array $options = [])
    {
        $instance = self::instance();
        $instance->index('users', $options);
        $instance->create('users', $options);
        $instance->show('users', $options);
        $instance->store('users', $options, ['validate']);
        $instance->route('post', 'users.login', 'login', '/users/login', $options);
        $instance->route('put', 'users.password_reset', 'resetPassword', '/users/password_reset', $options, ['validate']);
        $instance->update('users', $options);
        $instance->destroy('users', $options);
    }
}
