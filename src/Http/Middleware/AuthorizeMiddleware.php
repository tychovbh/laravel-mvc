<?php

namespace Tychovbh\Mvc\Http\Middleware;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AuthorizeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        $id = $request->route('id');
        $name = explode('.', get_route_info($request, 'as'));
        $model = 'App\\' .ucfirst(Str::camel(Str::singular($name[0])));
        $model = project_or_package_class('Model', $model);

        if ($id) {
            try {
                if (method_exists($model, 'trashed')) {
                    $model = $model::withTrashed()->findOrFail($id);
                } else {
                    $model = $model::findOrFail($id);
                }
            } catch (\Exception $exception) {
                abort(404, message('model.notfound', str_replace('App\\', '', $model), 'ID', $id));
                return;
            }
        }

        Arr::forget($name, 0);
        $name  = implode(' ', $name);
        $name = str_replace(' ', '', lcfirst(ucwords($name)));


        if (cant($name, $model)) {
            abort(401, message('auth.unauthorized'));
        }

        return $next($request);
    }
}
