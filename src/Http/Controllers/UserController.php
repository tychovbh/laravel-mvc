<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Mail;
use Tychovbh\Mvc\Mail\UserCreated;
use Tychovbh\Mvc\Mail\UserVerify;
use Tychovbh\Mvc\PasswordReset;
use Tychovbh\Mvc\TokenType;
use Tychovbh\Mvc\Repositories\TokenRepository;
use Tychovbh\Mvc\Repositories\PasswordResetRepository;
use Tychovbh\Mvc\Repositories\UserRepository;

/**
 * @property TokenRepository $tokens
 * @property PasswordResetRepository passwordResets
 * @property UserRepository repository
 */
class UserController extends AbstractController
{
    /**
     * FieldController constructor.
     * @param TokenRepository $tokens
     * @param PasswordResetRepository $passwordResets
     * @throws \Exception
     */
    public function __construct(TokenRepository $tokens, PasswordResetRepository $passwordResets)
    {
        $this->tokens = $tokens;
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

        if (config('mvc-mail.messages.user.verify')) {
            $token = $this->tokens->save([
                'type' => TokenType::VERIFY_EMAIL,
                'id' => $user->id,
                'email' => $user->email
            ]);
            $data['link'] = str_replace('{reference}', $token->reference, config('mvc-auth.url'));
            Mail::send(new UserVerify($data));
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
        $token = $request->input('token');

        try {
            $invite = $this->tokens->findBy('reference', $token);
            $request->merge(['token' => $invite->value]);
            $user = parent::store($request);
            $this->tokens->destroy([$invite->id]);
            return $user;
        } catch (\Exception $exception) {
            //
        }

        return abort(404, message('model.notfound', 'Invite', 'Reference', $token));
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
