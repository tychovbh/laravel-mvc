<?php

namespace Tychovbh\Mvc\Providers;

use Tychovbh\Mvc\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Tychovbh\Mvc\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $policies = [];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    /**
     * Boot the authentication services for the application.
     *
     * @param UserRepository $users
     * @return void
     */
    public function boot(UserRepository $users)
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function (Request $request) use ($users) {
            if (!$request->route()) {
                // This is not a request
                return new User;
            }

            if (!$request->header('Authorization')) {
                abort(400, message('auth.token.missing'));
            }

            $token = str_replace('Bearer ', '', $request->header('Authorization'));

            try {
                token_validate($token);
            } catch (\Exception $exception) {
                abort(400, message('auth.token.expired'));
            }

            try {
                $route = get_route_info($request, 'as');
                $login_field = config('mvc-auth.login_field', 'email');
                if ($request->has('login_field')) {
                    $login_field = $request->input('login_field');
                }
                if ($request->has([$login_field, 'password']) && $route === 'auth.login') {
                    return $users->login($request->toArray());
                }

                return $users->find(token_value($token));
            } catch (ModelNotFoundException $exception) {
                abort(404, message('auth.notfound'));
            }
        });
    }
}
