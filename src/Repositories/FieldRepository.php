<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Repositories;

use Tychovbh\Mvc\Field;

class FieldRepository extends AbstractRepository implements Repository
{
    /**
     * InputRepository constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->model = new Field();
        parent::__construct();
    }
}
