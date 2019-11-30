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
        $instance->route('post', 'users.login', 'login', '/users/login', $options, ['validate']);
        $instance->route('put', 'users.password_reset', 'resetPassword', '/users/password_reset', $options, ['validate']);
        if (env('AUTH_EMAIL_VERIFY_ENABLED')) {
            $instance->route('post', 'users.send_verify_email', 'sendVerifyEmail', '/users/send_verify_email', $options, ['validate']);
        }
        $instance->update('users', $options, ['validate']);
        $instance->destroy('users', $options);
    }
}
