<?php

namespace Tychovbh\Mvc\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class CacheMiddleware
{
    /**
     * Handle an incoming request.
     * TODO do not cache exceptions
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->method() !== 'GET') {
            return $next($request);
        }

        if (!config('mvc-cache.enabled') || boolean($request->get('cache_disabled'))) {
            return $next($request);
        }

        $path = config('app.url') . $request->getPathInfo();
        $params = $request->toArray();

        if ($params) {
            $path .= '?' . http_build_query($params);
        }

        $cache_minutes = get_route_info($request, 'cache_minutes') ?? config('mvc-cache.minutes');
        $route_name = get_route_info($request, 'as');
        $tags = [$route_name];
        $id = get_route_info($request, 'id');
        if ($id) {
            $tags[] = $route_name . '.' . $id;
        }

        if (Cache::tags($tags)->has($path)) {
            return Cache::tags($tags)->get($path);
        }

        $response = $next($request);
        if (in_array($response->getStatusCode(), [200, 201])) {
            Cache::tags($tags)->put($path, $response, now()->addMinutes($cache_minutes));
        }
        return $response;
    }
}
