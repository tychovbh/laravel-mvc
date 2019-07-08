<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Controllers;

use Tychovbh\Mvc\Http\Resources\ElementResource;
use Tychovbh\Mvc\Repositories\ElementRepository;

class ElementController extends AbstractController
{
    /**
     * @var string
     */
    public $resource = ElementResource::class;

    /**
     * ElementController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->repository = new ElementRepository();
        parent::__construct();
    }
}
