<?php

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
            throw new \Exception('Repository '. $repository .' not found!');
        }

        $class = str_replace([
            $class->getNamespaceName(),
            'Controller'
        ], [
            config('env') === 'testing' ? 'Tychovbh\\Mvc\\Tests\\App' : 'App\\Repositories',
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
            config('env') === 'testing' ? 'Tychovbh\\Tests\\Mvc\\App' : 'App',
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
            'Test',
            'Controller'
        ], [
            'App\\Http\\Resources',
            'Resource',
            'Resource'
        ], $resource);

        if (!class_exists($class)) {
            throw new \Exception('Resource not found!');
        }
        return $class;
    }
}
