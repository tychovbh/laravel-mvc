<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Mail;
use Tychovbh\Mvc\Mail\UserCreated;
use Tychovbh\Mvc\PasswordReset;
use Tychovbh\Mvc\Repositories\InviteRepository;
use Tychovbh\Mvc\Repositories\PasswordResetRepository;
use Tychovbh\Mvc\Repositories\UserRepository;

/**
 * @property InviteRepository invites
 * @property PasswordResetRepository passwordResets
 * @property UserRepository repository
 */
class UserController extends AbstractController
{
    /**
     * FieldController constructor.
     * @param InviteRepository $invites
     * @param PasswordResetRepository $passwordResets
     * @throws \Exception
     */
    public function __construct(InviteRepository $invites, PasswordResetRepository $passwordResets)
    {
        $this->invites = $invites;
        $this->passwordResets = $passwordResets;
        parent::__construct();
    }

    /**
     * Store User Resource
     * @param Request $request
     * @return JsonResource
     */
    public function store(Request $request): JsonResource
    {
        $user = $request->has('token') ? $this->storeFromInvite($request) : parent::store($request);

        if (config('mvc-mail.messages.user.store')) {
            Mail::send(new UserCreated($user->email));
        }

        return $user;
    }

    /**
     * Request to reset password
     * @param Request $request
     * @return JsonResource
     */
    public function resetPassword(Request $request): JsonResource
    {
        $token = $request->input('token');
        try {
            $passwordReset = $this->passwordResets->findBy('token', $token);
            $user = parent::update($request, $passwordReset->user->id);
            PasswordReset::where('email', $passwordReset->email)->delete();
            return $user;
        } catch (\Exception $exception) {
            //
        }

        return abort(404, message('model.notfound', 'Password Reset', 'Reference', $token));
    }

    /**
     * Store User from Invite
     * @param Request $request
     * @return JsonResource
     */
    private function storeFromInvite(Request $request): JsonResource
    {
        $reference = $request->input('token');

        try {
            $invite = $this->invites->findBy('reference', $reference);
            $request->merge(['token' => $invite->token]);
            $user = parent::store($request);
            $this->invites->model::where('reference', $reference)->delete();
            return $user;
        } catch (\Exception $exception) {
            //
        }

        return abort(404, message('model.notfound', 'Invite', 'Reference', $reference));
    }

    /**
     * Login user
     * @param Request $request
     * @return JsonResource
     */
    public function login(Request $request): JsonResource
    {
        return new $this->resource($this->repository->login($request->toArray()));
    }
}
