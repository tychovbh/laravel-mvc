<?php

namespace Tychovbh\Mvc\Http\Controllers;

use Tychovbh\Mvc\Http\Resources\InviteResource;
use Tychovbh\Mvc\Repositories\InviteRepository;

class InviteController extends AbstractController
{
    /**
     * @var string
     */
    public $resource = InviteResource::class;

    /**
     * FieldController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->repository = new InviteRepository();
        parent::__construct();
    }
}
