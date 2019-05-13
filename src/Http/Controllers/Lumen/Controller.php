<?php

namespace Tychovbh\Mvc\Http\Controllers\Lumen;

use App\Http\Controllers\Rest;
use Laravel\Lumen\Routing\Controller as BaseController;
use Tychovbh\Mvc\Http\Controllers\Controller as IController;

class Controller extends BaseController implements IController
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
