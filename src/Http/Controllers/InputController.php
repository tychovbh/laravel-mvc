<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Controllers;

use Tychovbh\Mvc\Http\Resources\InputResource;
use Tychovbh\Mvc\Repositories\InputRepository;

class InputController extends AbstractController
{
    /**
     * @var string
     */
    public $resource = InputResource::class;

    /**
     * InputController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->repository = new InputRepository();
        parent::__construct();
    }
}
