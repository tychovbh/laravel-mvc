<?php
namespace Tychovbh\Mvc\Contracts;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Exception;

trait HasThirdPartyAuthentication
{
    public function boot()
    {
        parent::boot();

        Auth::viaRequest('third-party-authentication', function ($request) {
            if (!$request->header('Cookie') && !$request->header('Authorization')) {
                abort(400, message('auth.third-party-authentication.missing'));
            }

            $endpoint = config('mvc-auth.third_party_authentication');
            $token = $request->header('Cookie');
            $token_key = 'Cookie';

            if ($request->header('Authorization')) {
                $token = $request->header('Authorization');
                $token_key = 'Authorization';
            }

            $tags = ['users.authorize'];

            if (!config('mvc-cache.enabled')) {
                return $this->authenticate($endpoint, $token, $token_key);
            }

            return Cache::tags($tags)
                ->remember($token, now()->addHour(), function () use ($endpoint, $token, $token_key) {
                    return $this->authenticate($endpoint, $token, $token_key);
                });
        });
    }

    /**
     * Authenticate request.
     * @param string $endpoint
     * @param string $token
     * @param string $token_key
     * @return mixed
     * @throws Exception
     */
    private function authenticate(string $endpoint, string $token, string $token_key)
    {
        $response = Http::withHeaders([$token_key => $token])->get($endpoint)->json();

        if (!Arr::get($response, 'id')) {
            abort(400, message('auth.third-party-authentication.invalid'));
        }

        $class = project_or_package_class('Model', 'User');
        return new $class($response);
    }
}
