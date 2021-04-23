<?php

namespace Tychovbh\Mvc\Http\Middleware;

use Closure;
use Illuminate\Routing\Redirector;
use Tychovbh\Mvc\Http\Requests\Laravel\FormRequest;

class ValidateRequest
{
    /**
     * Handle an incoming request.
     * TODO expand this for Laravel applications
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        $formRequest = $this->createFormRequest($request);
        $formRequest->setContainer(app())->setRedirector(app()->make(Redirector::class));
        $formRequest->validateResolved();

        return $next($request);
    }

    /**
     * Create Form Request
     * @param $request
     * @return FormRequest
     * @throws \Exception
     */
    private function createFormRequest($request): FormRequest
    {
        $name = get_route_info($request, 'request');

        if ($name) {
            return $name::createFrom($request);
        }

        $pieces = explode('\\', get_route_info($request, 'uses'));
        $action = array_pop($pieces);
        $namespace = str_replace(['Tychovbh\Mvc', 'Controllers'], ['App', 'Requests'], implode('\\', $pieces));
        $uses = explode('@', $action);
        $name  = $namespace . '\\' . ucfirst($uses[1]) . str_replace('Controller', '', $uses[0]);
        $name = project_or_package_class('Request', $name);

        return $name::createFrom($request);
    }
}
