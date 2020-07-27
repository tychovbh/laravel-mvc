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

        $whitelist = config('security.whitelist');

        if (in_array($request->ip(), $whitelist)) {
            return $next($request);
        }

        if (config('security.logging')) {
            error('IP whitelist blocked!', [
                'ip' => $request->ip()
            ]);
        }

        return abort(401, message('auth.unauthorized'));
    }
}
