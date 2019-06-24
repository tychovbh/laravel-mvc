<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Controllers;

use Tychovbh\Mvc\Repositories\FormRepository;
use Tychovbh\Mvc\Http\Controllers\Laravel\Controller as BaseController;
use Tychovbh\Mvc\Http\Resources\FormResource;

class FormController extends BaseController
{
    /**
     * @var string
     */
    public $resource = FormResource::class;

    /**
     * TestUserController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->repository = new FormRepository();
        parent::__construct();
    }
}
