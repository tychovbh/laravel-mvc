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

        try {
            token_validate($data['token']);
        } catch (Exception $exception) {
            abort(400, message('auth.token.expired'));
        }

        $data = array_merge($data, (array)token_value($data['token']));

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
        if (!Arr::has($data, 'email')) {
            abort(404, message('login.notfound'));
        }

        try {
            $user = $this->findBy('email', $data['email']);
        } catch (Exception $exception) {
            abort(404, message('login.notfound'));
        }

        if (!Hash::check(Arr::get($data, 'password'), $user->password)) {
            abort(401, message('login.password.incorrect'));
        }

        return $user;
    }
}
