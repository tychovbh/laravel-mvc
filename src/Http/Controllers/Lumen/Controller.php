<?php

namespace Tychovbh\Mvc\Http\Controllers\Lumen;

use Tychovbh\Mvc\Http\Controllers\Rest;
use Laravel\Lumen\Routing\Controller as BaseController;
use Tychovbh\Mvc\Http\Controllers\Controller as IController;

/**
 * Class Controller
 * @property \Tychovbh\Mvc\Repositories\Repository repository
 * @property string resource
 * @property String controller
 * @package Tychovbh\Mvc\Http\Controllers\Lumen
 */
abstract class Controller extends BaseController implements IController
{
    use Rest;
}
