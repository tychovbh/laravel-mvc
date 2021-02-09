<?php
declare(strict_types=1);

use Tychovbh\Mvc\Collections\Collection;
use Tychovbh\Mvc\Collections\AbstractCollection;

class EntityCollection extends AbstractCollection implements Collection
{
    /**
     * @var string
     */
    protected $table = '{table}';

    /**
     * @var string
     */
    protected $update_by = 'name';

    /**
     * Records to add to the collection
     * @return array
     */
    public function records(): array
    {
        return [];
    }
}
