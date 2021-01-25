<?php

namespace Tychovbh\Mvc\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class AuthorizeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws Exception
     */
    public function handle($request, Closure $next)
    {
        $id = $request->route('id');
        $name = explode('.', get_route_info($request, 'as'));
        $model = 'App\\Models\\' .ucfirst(Str::singular($name[0]));
        $model = project_or_package_class('Model', $model);

        if ($id) {
            try {
                $model = $model::findOrFail($id);
            } catch (Exception $exception) {
                abort(404, message('model.notfound', str_replace('App\\', '', $model), 'ID', $id));
                return;
            }
        }

        Arr::forget($name, 0);
        $name  = implode(' ', $name);
        $name = str_replace(' ', '', lcfirst(ucwords($name)));

        $res = Gate::inspect($name, $model);
        if (!$res->allowed()) {
            abort(401, message('auth.unauthorized'));
        }

        return $next($request);
    }
}
