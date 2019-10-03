<?php

declare(strict_types=1);

namespace Tychovbh\Mvc\Routes;

interface Routes
{
    /**
     * Insert routes in project
     * @param array $options
     */
    public static function routes(array $options = []);

    /**
     * Get Route Instance
     * @return Routes
     */
    public static function instance(): Routes;

    /**
     * Define index route
     * @param string $name
     * @param array $options
     * @param array $middleware
     */
    public function index(string $name, array $options = [], array $middleware = []);

    /**
     * Define show route
     * @param string $name
     * @param array $options
     * @param array $middleware
     */
    public function show(string $name, array $options = [], array $middleware = []);

    /**
     * Define create route
     * @param string $name
     * @param array $options
     * @param array $middleware
     */
    public function create(string $name, array $options = [], array $middleware = []);

    /**
     * Define store route
     * @param string $name
     * @param array $options
     * @param array $middleware
     */
    public function store(string $name, array $options = [], array $middleware = []);

    /**
     * Define update route
     * @param string $name
     * @param array $options
     * @param array $middleware
     */
    public function update(string $name, array $options = [], array $middleware = []);

    /**
     * Define destroy route
     * @param string $name
     * @param array $options
     * @param array $middleware
     */
    public function destroy(string $name, array $options = [], array $middleware = []);

    /**
     * Define route
     * @param string $method
     * @param string $as
     * @param string $action
     * @param string $url
     * @param array $options
     * @param array $middleware
     */
    public function route(
        string $method,
        string $as,
        string $action,
        string $url,
        array $options = [],
        array $middleware = []
    );
}
