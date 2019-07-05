<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Controllers;

use Tychovbh\Mvc\Repositories\FormRepository;
use Tychovbh\Mvc\Http\Resources\FormResource;

class FormController extends AbstractController
{
    /**
     * @var string
     */
    public $resource = FormResource::class;

    /**
     * FormController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->repository = new FormRepository();
        parent::__construct();
    }
}
