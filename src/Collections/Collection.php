<?php
namespace Tychovbh\Mvc\Collections;

interface Collection
{
    /**
     * Records to add to the collection
     * @return array
     */
    public function records(): array;

    /**
     * Database table
     * @return string
     */
    public function table(): string;

    /**
     * Field to update by
     * @return string
     */
    public function updateBy(): string;
}
