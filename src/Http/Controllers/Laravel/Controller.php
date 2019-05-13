<?php

namespace Tychovbh\Mvc\Http\Controllers\Laravel;

use Tychovbh\Mvc\Http\Controllers\Rest;
use Illuminate\Routing\Controller as BaseController;
use Tychovbh\Mvc\Http\Controllers\Controller as IController;

/**
 * Class Controller
 * @property \Tychovbh\Mvc\Repositories\Repository repository
 * @property string resource
 * @property String controller
 * @package Tychovbh\Mvc\Http\Controllers\Laravel
 */
abstract class Controller extends BaseController implements IController
{
    use Rest;

    /**
     * AbstractController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->repository = repository(get_called_class());
        $this->resource = resource(get_called_class());
        $this->controller = controller(get_called_class());
    }
}
