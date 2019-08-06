<?php

declare(strict_types=1);

namespace Tychovbh\Mvc\Routes;

use Illuminate\Support\Arr;

class Invite implements Routes
{
    /**
     * @param array $options
     */
    public static function routes(array $options = [])
    {
        $app = app();

        if (is_application() === 'lumen') {
            $attributes = array_merge([
                'as' => 'invites.store',
                'namespace' => 'Tychovbh\Mvc\Http\Controllers',
                'uses' => 'InviteController@store'
            ], Arr::get($options, 'store', []));

            $attributes['middleware'] = array_merge(Arr::get($attributes, 'middleware'), ['validate']);
            $app->router->post('/invites', $attributes);
        }

        if (is_application() === 'laravel') {
            $app['router']
                ->resource('invites', 'InviteController')
                ->only(['store'])
                ->middleware('validate');
        }
    }
}
