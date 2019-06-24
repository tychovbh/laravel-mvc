<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Repositories;

use Tychovbh\Mvc\Input;

class InputRepository extends AbstractRepository implements Repository
{
    /**
     * InputRepository constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->model = new Input();
        parent::__construct();
    }
}
