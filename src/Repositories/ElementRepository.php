<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Repositories;

use Tychovbh\Mvc\Element;

class ElementRepository extends AbstractRepository implements Repository
{
    /**
     * ElementRepository constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->model = new Element();
        parent::__construct();
    }
}
