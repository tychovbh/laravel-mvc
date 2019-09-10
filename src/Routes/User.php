<?php

declare(strict_types=1);

namespace Tychovbh\Mvc\Routes;

use Tychovbh\Mvc\Http\Controllers\UserController;

class User extends AbstractRoutes implements Routes
{
    /**
     * @param array $options
     */
    public static function routes(array $options = [])
    {
        $instance = self::instance();
        $instance->show('users', $options);
        $instance->store('users', $options, ['validate']);
        $instance->route('post', 'users.login', 'login', '/users/login', $options);
        $instance->update('users.password_reset', array_merge([
            'update' => [
                'uses' => UserController::class . '@resetPassword',
                'url' => '/users/password_reset',
            ]
        ], $options), ['validate']);
        $instance->index('users', $options);
    }
}
