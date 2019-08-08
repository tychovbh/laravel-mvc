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
     * Define show route
     * @param string $name
     * @param array $options
     * @param array $middleware
     */
    public function show(string $name, array $options = [], array $middleware = []);

    /**
     * Define store route
     * @param string $name
     * @param array $options
     * @param array $middleware
     */
    public function store(string $name, array $options = [], array $middleware = []);
}
