<?php

namespace Tychovbh\Mvc\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Tychovbh\Mvc\Models\TokenType;
use Tychovbh\Mvc\Repositories\TokenRepository;

class InviteController extends AbstractController
{
    /**
     * InviteController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->repository = new TokenRepository();
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return JsonResource
     */
    public function store(Request $request): JsonResource
    {
        $request->offsetSet('type', TokenType::INVITE_USER);
        return parent::store($request);
    }
}
