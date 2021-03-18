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
        $instance->route('get', 'wildcards.index', 'index', '/{connection}/{table}');
        $instance->route('get', 'wildcards.create', 'create', '/{connection}/{table}/create');
        $instance->route('get', 'wildcards.edit', 'edit', '/{connection}/{table}/{id}/edit');
        $instance->route('get', 'wildcards.show', 'show', '/{connection}/{table}/{id}');
        $instance->route('post', 'wildcards.store', 'store', '/{connection}/{table}');
        $instance->route('put', 'wildcards.update', 'update', '/{connection}/{table}/{id}');
        $instance->route('delete', 'wildcards.destroy', 'destroy', '/{connection}/{table}/{id}');
    }
}
