<?php

namespace Tychovbh\Tests\Mvc\App;

use Tychovbh\Mvc\Http\Controllers\AbstractController;

class TestUserController extends AbstractController
{
    /**
     * @var string
     */
    public $resource = TestUserResource::class;

    /**
     * TestUserController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->repository = new TestUserRepository;
        parent::__construct();
    }
}
