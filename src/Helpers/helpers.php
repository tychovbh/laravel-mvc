<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use ReallySimpleJWT\Token;
use Tychovbh\Mvc\Repositories\Repository;

if (!function_exists('repository')) {
    /**
     * Repository factory
     * @param string $repository
     * @return Repository
     * @throws Exception
     */
    function repository(string $repository): Repository
    {
        try {
            $class = new \ReflectionClass($repository);
        } catch (\Exception $exception) {
            throw new \Exception('Repository ' . $repository . ' not found!');
        }

        $class = str_replace([
            $class->getNamespaceName(),
            'Controller'
        ], [
            'App\\Repositories',
            'Repository'
        ], $repository);

        if (!class_exists($class)) {
            throw new \Exception('Repository ' . $class . ' not found!');
        }

        return new $class;
    }
}

if (!function_exists('model')) {

    /**
     * Model factory
     * @param string $model
     * @return mixed
     * @throws Exception
     */
    function model(string $model)
    {
        try {
            $class = new \ReflectionClass($model);
        } catch (\Exception $exception) {
            throw new \Exception('Model not found!');
        }

        $class = str_replace([
            $class->getNamespaceName(),
            'Repository',
        ], [
            'App',
            '',
        ], $model);

        if (!class_exists($class)) {
            throw new \Exception('Model ' . $class . ' not found!');
        }
        return new $class;

    }
}


if (!function_exists('controller')) {
    /**
     * Controller factory
     * @param string $controller
     * @return string
     * @throws Exception
     */
    function controller(string $controller): String
    {
        try {
            $class = new \ReflectionClass($controller);
        } catch (\Exception $exception) {
            throw new \Exception('Controller not found!');
        }
        $class = str_replace([
            $class->getNamespaceName() . '\\',
            'Controller'
        ], [
            '',
            ''
        ], $controller);

        $class = strtolower(
            preg_replace('/(?<!\ )[A-Z]/', '_$0', lcfirst($class))
        );
        return strtolower($class);
    }
}

if (!function_exists('resource')) {

    /**
     * Resource factory
     * @param string $resource
     * @return string
     * @throws Exception
     */
    function resource(string $resource): string
    {
        try {
            $class = new \ReflectionClass($resource);
        } catch (\Exception $exception) {
            throw new \Exception('Resource not found!');
        }

        $class = str_replace([
            $class->getNamespaceName(),
            'Controller'
        ], [
            'App\\Http\\Resources',
            'Resource'
        ], $resource);

        if (!class_exists($class)) {
            throw new \Exception('Resource not found: ' . $class);
        }
        return $class;
    }
}

if (!function_exists('has_column')) {
    /**
     * Check if model has column
     * @param Model $model
     * @param string $key
     * @return mixed
     */
    function has_column(Model $model, string $key)
    {
        return Schema::hasColumn($model->getTable(), $key);
    }
}

if (!function_exists('error')) {

    /**
     * Log warning
     *
     * @param string $message
     * @param array $context
     */
    function error(string $message, $context = [])
    {
        Log::error($message, $context);
    }
}

if (!function_exists('warning')) {

    /**
     * Log warning
     *
     * @param string $message
     * @param array $context
     */
    function warning(string $message, $context = [])
    {
        Log::warning($message, $context);
    }
}

if (!function_exists('info')) {

    /**
     * Log warning
     *
     * @param string $message
     * @param array $context
     */
    function info(string $message, $context = [])
    {
        Log::info($message, $context);
    }
}

if (!function_exists('message')) {

    /**
     * Generate response message.
     * @param string $message
     * @param mixed ...$params
     * @return string
     */
    function message(string $message, ...$params)
    {
        $config = config('messages');
        $message = Arr::get($config, $message) ?? Arr::get($config, 'server.error');

        return $params ? sprintf($message, ...$params) : $message;
    }
}

if (!function_exists('is_application')) {

    /**
     * Check Application type
     * @return string
     */
    function is_application(): string
    {
        $app = app();
        if ($app instanceof \Illuminate\Foundation\Application && $app->runningInConsole()) {
            return 'laravel';
        } elseif ($app instanceof \Laravel\Lumen\Application) {
            return 'lumen';
        }

        return 'laravel';
    }
}

if (!function_exists('get_route_info')) {

    /**
     * @param Request $request
     * @param $key
     * @return mixed|string
     */
    function get_route_info(Request $request, $key)
    {
        $route = (array)$request->route();
        foreach ($route as $items) {
            if (is_array($items) && Arr::has($items, $key)) {
                return $items[$key];
            }
        }
        return null;
    }
}

if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}

if (!function_exists('random_string')) {
    /**
     * Random string
     * @param int $length
     * @return string
     */
    function random_string(int $length = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $string .= $characters[$index];
        }

        return $string;
    }
}

if (!function_exists('token')) {
    /**
     * Generate token
     * TODO fix front-end login issue token expired and set addMonth to addDay
     * @param mixed $data
     * @return string
     */
    function token($data): string
    {
        return Token::create(
            $data,
            config('mvc-auth.secret'),
            time() + config('mvc-auth.expiration'),
            config('mvc-auth.id')
        );
    }
}


if (!function_exists('token_validate')) {
    /**
     * validate token
     * @param string $token
     * @return bool
     */
    function token_validate(string $token)
    {
        return Token::validate($token, config('mvc-auth.secret'));
    }
}

if (!function_exists('token_value')) {
    /**
     * Get token value
     * @param string $token
     * @return mixed
     */
    function token_value(string $token)
    {
        return Token::getPayload($token, config('mvc-auth.secret'))['user_id'];
    }
}
