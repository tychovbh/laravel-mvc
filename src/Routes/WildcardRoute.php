<?php

declare(strict_types=1);

namespace Tychovbh\Mvc\Routes;

class WildcardRoute extends AbstractRoutes implements Routes
{
    /**
     * @param array $options
     */
    public static function routes(array $options = [])
    {
        $instance = self::instance();
        $instance->route('get', 'wildcards.index', 'index', '/{connection}/{name}');
        $instance->route('get', 'wildcards.create', 'create', '/{connection}/{name}/create');
        $instance->route('get', 'wildcards.edit', 'edit', '/{connection}/{name}/{id}/edit');
        $instance->route('get', 'wildcards.show', 'show', '/{connection}/{name}/{id}');
        $instance->route('post', 'wildcards.store', 'store', '/{connection}/{name}');
        $instance->route('put', 'wildcards.update', 'update', '/{connection}/{name}/{id}');
        $instance->route('delete', 'wildcards.destroy', 'destroy', '/{connection}/{name}/{id}');
    }
}
