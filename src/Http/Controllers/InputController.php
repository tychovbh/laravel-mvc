<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Controllers;

use Tychovbh\Mvc\Http\Resources\InputResource;
use Tychovbh\Mvc\Repositories\InputRepository;
use Tychovbh\Mvc\Http\Controllers\Laravel\Controller as BaseController;

class InputController extends BaseController
{
    /**
     * @var string
     */
    public $resource = InputResource::class;

    /**
     * TestUserController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->repository = new InputRepository();
        parent::__construct();
    }
}
