<?php

namespace Tychovbh\Mvc\Collections;

abstract class AbstractCollection
{
    /**
     * @var string
     */
    protected $table = '';

    /**
     * @var string
     */
    protected $update_by = '';

    /**
     * Database table
     * @return string
     */
    public function table(): string
    {
        return $this->table;
    }

    /**
     * Field to update by
     * @return string
     */
    public function updateBy(): string
    {
        return $this->update_by;
    }
}
