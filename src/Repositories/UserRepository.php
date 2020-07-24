<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Repositories;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Tychovbh\Mvc\User;

class UserRepository extends AbstractRepository implements Repository
{
    /**
     * Override save to store channel
     * @param array $data
     * @return User
     */
    public function save(array $data): User
    {
        try {
            $data = $this->dataFromToken($data);
        } catch (Exception $exception) {
            abort(400, $exception->getMessage());
        }

        return parent::save($data);
    }

    /**
     * Get data from token.
     * @param array $data
     * @return array
     * @throws Exception
     */
    private function dataFromToken(array $data): array
    {
        if (!Arr::get($data, 'token')) {
            return $data;
        }

        if (!token_validate($data['token']->value)) {
            abort(400, message('auth.token.expired'));
        }

        $data = array_merge($data, (array)token_value($data['token']->value));
        $data['token'] = $data['token']->reference;

        try {
            $this->findBy('email', $data['email']);
        } catch (Exception $exception) {
            return $data;
        }

        throw new Exception(message('model.unique', 'email'));
    }

    /**
     * Login user.
     * @param array $data
     * @return User
     */
    public function login(array $data): User
    {
        $login_field = (string)config('mvc-auth.login_field', 'email');
        if (Arr::has($data, 'login_field')) {
            $login_field = $data['login_field'];
        }

        if (!Arr::has($data, $login_field)) {
            abort(404, message('login.notfound'));
        }

        try {
            $user = $this->findBy($login_field, $data[$login_field]);
        } catch (Exception $exception) {
            abort(404, message('login.notfound'));
        }

        if (config('mvc-auth.email_verify_enabled') && !$user->email_verified_at) {
            abort(401, message('login.email.unverified'));
        }

        if (!Hash::check(Arr::get($data, 'password'), $user->password)) {
            abort(401, message('login.password.incorrect'));
        }

        return $user;
    }
}
