<?php

namespace Tychovbh\Mvc\Http\Controllers\Lumen;

use App\Http\Controllers\Rest;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
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
