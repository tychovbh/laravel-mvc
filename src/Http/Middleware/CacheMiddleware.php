<?php

namespace Tychovbh\Mvc\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class CacheMiddleware
{
    /**
     * Handle an incoming request.
     * TODO do not cache exceptions
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (!config('app.cache') || boolean($request->get('cache_disabled'))) {
            return $next($request);
        }

        $path = config('app.url') . $request->getPathInfo();
        $params = $request->toArray();

        if ($params) {
            $path .= '?' . http_build_query($params);
        }

        // Cast array because return type from Laravel is completely off :(
        $cache_minutes = get_route_info($request, 'cache_minutes') ?? (60 * 24);
        $route_name = get_route_info($request, 'as');
        $tags = [$route_name];
        $id = get_route_info($request, 'id');
        if ($id) {
            $tags[] = $route_name . '.' . $id;
        }
        $response = Cache::tags($tags)->remember($path, $cache_minutes, function() use ($request, $next) {
            return $next($request);
        });

        return $response;
    }
}
