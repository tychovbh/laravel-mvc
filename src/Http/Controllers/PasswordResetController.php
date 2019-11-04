<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Tychovbh\Mvc\Repositories\TokenRepository;
use Tychovbh\Mvc\Repositories\UserRepository;
use Tychovbh\Mvc\TokenType;

/**
 * @property UserRepository users
 */
class PasswordResetController extends AbstractController
{
    /**
     * InviteController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->repository = new TokenRepository();
        $this->users = new UserRepository();
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return JsonResource
     */
    public function store(Request $request): JsonResource
    {
        $user = $this->users->findBy('email', $request->input('email'));
        $request->offsetSet('id', $user->id);
        $request->offsetSet('type', TokenType::PASSWORD_RESET);
        $request->offsetSet('user', $user->toArray());
        return parent::store($request);
    }
}
