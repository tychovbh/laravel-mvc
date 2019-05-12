<?php

namespace Tychovbh\Mvc\Http\Middleware;

use Closure;

class ValidateMiddleware
{
    /**
     * Handle an incoming request.
     * TODO expand this for Laravel applications
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $uses = explode('@', str_replace(['App\\Http\\Controllers\\', 'Controller'], ['', ''], get_route_info($request, 'uses')));
        $name = '\\App\\Http\\Requests\\' . ucfirst($uses[1]) . $uses[0];
        $formRequest = $name::createFrom($request);
        $formRequest->setContainer(app());
        $formRequest->validateResolved();

        $response = $next($request);

        return $response;
    }
}
