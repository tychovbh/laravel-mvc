<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Controllers;

use Tychovbh\Mvc\Http\Resources\FieldResource;
use Tychovbh\Mvc\Http\Controllers\Laravel\Controller as BaseController;
use Tychovbh\Mvc\Repositories\FieldRepository;

class FieldController extends BaseController
{
    /**
     * @var string
     */
    public $resource = FieldResource::class;

    /**
     * TestUserController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->repository = new FieldRepository();
        parent::__construct();
    }
}
