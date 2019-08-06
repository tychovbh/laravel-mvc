<?php

declare(strict_types=1);

namespace Tychovbh\Mvc\Routes;

use Illuminate\Support\Arr;
use Tychovbh\Mvc\Http\Controllers\InviteController;
use Tychovbh\Mvc\Http\Requests\StoreInvite;

class Invite implements Routes
{
    /**
     * @param array $options
     */
    public static function routes(array $options = [])
    {
        $app = app();

        if (is_application() === 'lumen') {
            $app->router->post('/invites', array_merge([
                    'as' => 'invites.store',
                    'middleware' => ['validate'],
                    'uses' => StoreInvite::class . '@store'
                ], Arr::get($options, 'store', []))
            );
        }

        if (is_application() === 'laravel') {
            $app['router']
                ->resource('invites', InviteController::class)
                ->only(['store'])
                ->middleware('validate');
        }
    }
}
