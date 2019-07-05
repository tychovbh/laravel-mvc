<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Repositories;

use Illuminate\Support\Arr;
use Tychovbh\Mvc\Field;

/**
 * @property InputRepository inputs
 */
class FieldRepository extends AbstractRepository implements Repository
{
    /**
     * FieldRepository constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->model = new Field();
        parent::__construct();
        $this->inputs = new InputRepository();
    }
}
