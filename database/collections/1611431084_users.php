<?php
declare(strict_types=1);

use Tychovbh\Mvc\Collections\Collection;
use Tychovbh\Mvc\Collections\AbstractCollection;

class UsersCollection extends AbstractCollection implements Collection
{
    /**
     * @var string
     */
    protected $table = 'users';

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
        return [
            [
                'name' => 'Jan',
                'email' => 'jan@live.com'
            ],
            [
                'name' => 'piet',
                'email' => 'piet@live.com'
            ],
        ];
    }
}
