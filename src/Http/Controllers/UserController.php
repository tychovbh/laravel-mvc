<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Mail;
use Tychovbh\Mvc\Mail\UserCreated;
use Tychovbh\Mvc\Repositories\InviteRepository;

/**
 * @property InviteRepository invites
 */
class UserController extends AbstractController
{
    /**
     * FieldController constructor.
     * @param InviteRepository $invites
     * @throws \Exception
     */
    public function __construct(InviteRepository $invites)
    {
        $this->invites = $invites;
        parent::__construct();
    }

    /**
     * Store User Resource
     * @param Request $request
     * @return JsonResource
     */
    public function store(Request $request): JsonResource
    {
        $user = $request->input('token') ? $this->storeFromInvite($request) : parent::store($request);

        if (config('mvc-mail.messages.user.store')) {
            Mail::send(new UserCreated($user->email));
        }

        return $user;
    }

    /**
     * Store User from Invite
     * @param Request $request
     * @return JsonResource
     */
    private function storeFromInvite(Request $request)
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
}
