<?php
namespace Tychovbh\Tests\Mvc\App;

use Tychovbh\Mvc\Http\Controllers\Laravel\Controller;

class TestUserController extends Controller
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
