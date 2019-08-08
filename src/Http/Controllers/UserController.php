<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Mail;
use Tychovbh\Mvc\Http\Resources\UserResource;
use Tychovbh\Mvc\Mail\UserCreated;
use Tychovbh\Mvc\Repositories\InviteRepository;
use Tychovbh\Mvc\Repositories\UserRepository;

/**
 * @property InviteRepository invites
 */
class UserController extends AbstractController
{
    /**
     * @var string
     */
    public $resource = UserResource::class;

    /**
     * FieldController constructor.
     * @param InviteRepository $invites
     * @throws \Exception
     */
    public function __construct(InviteRepository $invites)
    {
        $this->repository = new UserRepository();
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
        if ($request->input('token')) {
            return $this->storeFromInvite($request);
        }

        $user = parent::store($request);
        Mail::send(new UserCreated($user->email));
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
            abort(404, message('model.notfound', 'Invite', 'Reference', $reference));
        }
    }
}
