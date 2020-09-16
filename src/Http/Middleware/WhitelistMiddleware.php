<?php

namespace Tychovbh\Mvc\Http\Middleware;

use Closure;

class WhitelistMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('origin')) {
            return $next($request);
        }

        $whitelist = config('mvc-security.whitelist', []);
        $disabled = get_route_info($request, 'disabled', []);

        if (in_array($request->ip(), $whitelist) || in_array('whitelist', $disabled)) {
            return $next($request);
        }

        if (config('security.logging')) {
            error('IP whitelist blocked!', [
                'ip' => $request->ip()
            ]);
        }

        return abort(401, message('security.unauthorized'));
    }
}
