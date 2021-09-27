<?php

namespace Tychovbh\Mvc\Contracts;

use Illuminate\Support\Arr;

/**
 * Trait HasOptions
 * @package Tychovbh\Mvc\Contracts
 * @property array options
 * @property array casts
 * @property array fillable
 * @property array attributes
 */
trait HasOptions
{
    /**
     * Initialize the has files trait for an instance.
     *
     * @return void
     */
    public function initializeHasOptions()
    {
        $this->casts['options'] = 'array';
        $this->fillable[] = 'options';
    }

    /**
     * Get value from Options
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function option(string $key, $default = null): mixed
    {
        return Arr::get($this->options ?? [], $key, $default);
    }

    /**
     * Set Option
     * @param string $key
     * @param mixed|null $value
     */
    public function setOption(string $key, mixed $value = null)
    {
        if (!$this->options) {
            $this->options = [];
        }

        Arr::set($this->options, $key, $value);
    }

    /**
     * Set Options
     * @param array|null $options
     */
    public function setOptionsAttribute(array $options = null)
    {
        $this->attributes['options'] = !$options ? null : json_encode(array_merge($this->options ?? [], $options));
    }
}
