<?php

namespace Tychovbh\Mvc;

use Illuminate\Support\Facades\Storage as LaravelStorage;

class Storage
{

    /**
     * Store file
     * @param string $path
     * @param mixed $file
     * @return string
     */
    public static function store(string $path, mixed $file, string $disk = 'local'): string
    {
        LaravelStorage::disk($disk)->put($path, $file, 'public');
        return $path . '/' . $file->hashName();
    }

    /**
     * Check if file in storage exists
     * @param string $path
     * @return string
     */
    public static function exists(string $path, string $disk = 'local'): string
    {
        return LaravelStorage::disk($disk)->exists($path);
    }

    /**
     * Delete file from storage
     * @param array|string $paths
     * @param string $disk
     */
    public static function delete(array|string $paths, string $disk = 'local')
    {
        $paths = is_array($paths) ? $paths : [$paths];

        $paths = array_filter($paths, function ($path) use ($disk) {
            return self::exists($path, $disk);
        });

        $paths = array_map(function ($path) {
            return $path;
        }, $paths);

        if (!$paths) {
            return;
        }

        LaravelStorage::disk($disk)->delete($paths);
    }
}
