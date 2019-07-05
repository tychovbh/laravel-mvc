<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Controllers;

use Tychovbh\Mvc\Http\Resources\FieldResource;
use Tychovbh\Mvc\Repositories\FieldRepository;

class FieldController extends AbstractController
{
    /**
     * @var string
     */
    public $resource = FieldResource::class;

    /**
     * FieldController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->repository = new FieldRepository();
        parent::__construct();
    }
}
