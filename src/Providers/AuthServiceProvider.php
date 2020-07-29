<?php

namespace Tychovbh\Mvc\Providers;

use Illuminate\Support\Arr;
use ReallySimpleJWT\Exception\ValidateException;
use Tychovbh\Mvc\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Tychovbh\Mvc\TokenType;
use Tychovbh\Mvc\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $policies = [];

    /**
     * Register any application services.
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

            $route = get_route_info($request, 'as');
            $login_field = config('mvc-auth.login_field', 'email');

            if ($request->has('login_field')) {
                $login_field = $request->input('login_field');
            }

            if ($request->has([$login_field, 'password']) && $route === 'auth.login') {
                return $users->login($request->toArray());
            }
            // TODO until here

            if (!$request->header('Authorization')) {
                abort(400, message('auth.token.missing'));
            }

            $token = str_replace('Bearer ', '', $request->header('Authorization'));

            try {
                $value = token_value($token);
                $value = is_array($value) ? $value : ['id' => $value];
            } catch (ValidateException $exception) {
                abort(400, message('auth.token.invalid'));
            }

            $token_type = Arr::get($value, 'type', '');
            $allowed_routes = Arr::get($value, 'routes', null);

            if ($allowed_routes && !Arr::has($allowed_routes, $route)) {
                abort(400, message('auth.unauthorized'));
            }

            if ($token_type !== TokenType::API_KEY && !token_validate($token)) {
                abort(400, message('auth.token.expired'));
            }

            try {
                return $users->find($value['id']);
            } catch (ModelNotFoundException $exception) {
                abort(404, message('auth.notfound'));
            }

            return new User;
        });
    }
}
