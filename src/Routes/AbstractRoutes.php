<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Routes;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class AbstractRoutes
{
    /**
     * @var null
     */
    private static $instance = null;

    /**
     * AbstractRoutes constructor.
     */
    private function __construct()
    {
        //
    }

    /**
     * Get Route Instance
     * @return Routes
     */
    public static function instance(): Routes
    {
        if (self::$instance === null) {
            $class = get_called_class();
            self::$instance = new $class();
        }

        return self::$instance;
    }

    /**
     * Define show route
     * @param string $name
     * @param array $options
     * @param array $middleware
     */
    public function show(string $name, array $options = [], array $middleware = [])
    {

        $this->route('get', $name, 'show', '/' . $name . '/{id}', $options, $middleware);
    }

    /**
     * Define store route
     * @param string $name
     * @param array $options
     * @param array $middleware
     */
    public function store(string $name, array $options = [], array $middleware = [])
    {
        $this->route('post', $name, 'store', '/' . $name, $options, $middleware);
    }

    /**
     * Define route
     * @param string $method
     * @param string $name
     * @param string $action
     * @param string $url
     * @param array $options
     * @param array $middleware
     */
    private function route(string $method, string $name, string $action, string $url, array $options = [], array $middleware = [])
    {
        $app = app();
        $singular = Str::singular($name);
        $as = $name . '.' . $action;
        $namespace = Arr::get($options, $action . '.namespace', 'Tychovbh\Mvc\Http\Controllers') . '\\';
        $uses = Arr::get($options, $action . '.uses', $namespace . ucfirst($singular) . 'Controller@' . $action);
        $middleware = array_merge(Arr::get($options, $action . '.middleware', []), $middleware);
        Arr::forget($options, $action . '.middleware');

        if (is_application() === 'lumen') {
            $app->router->{$method}($url, array_merge([
                'as' => $as,
                'uses' => $uses,
                'middleware' => $middleware
            ], Arr::get($options, $action, [])));
        }

        if (is_application() === 'laravel') {
            $route = $app['router']->{$method}($url, $uses)
                ->name($as);

            if ($middleware) {
                $route->middleware($middleware);
            }
        }
    }
}
