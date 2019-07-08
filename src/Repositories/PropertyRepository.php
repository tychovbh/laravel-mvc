<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Repositories;

use Tychovbh\Mvc\Property;

class PropertyRepository extends AbstractRepository implements Repository
{
    /**
     * FieldRepository constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->model = new Property();
        parent::__construct();
    }
}
