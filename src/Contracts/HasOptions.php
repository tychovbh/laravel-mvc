<?php
namespace Tychovbh\Mvc\Contracts;

use Illuminate\Support\Arr;

trait HasOptions
{
    /**
     * Initialize the has files trait for an instance.
     *
     * @return void
     */
    public function initializeSoftDeletes()
    {
        $this->casts['options'] = 'array';
        $this->fillables[] = 'options';
    }

    /**
     * Get value from Options
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function option(string $key, $default = null)
    {
        return Arr::get($this->options ?? [], $key, $default);
    }

    /**
     * @param array|null $options
     */
    public function setOptionsAttribute(array $options = null)
    {
        $this->attributes['options'] = !$options ? null : json_encode(array_merge($this->options, $options));
    }
}
