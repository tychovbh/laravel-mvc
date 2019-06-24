<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Repositories;

use Tychovbh\Mvc\Form;

class FormRepository extends AbstractRepository implements Repository
{
    /**
     * FormRepository constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->model = new Form();
        parent::__construct();
    }
}
