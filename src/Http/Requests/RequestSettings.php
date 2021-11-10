<?php
namespace Tychovbh\Mvc\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\JsonResponse;

trait RequestSettings
{
    /**
     * Change response code
     * @param array $errors
     * @return JsonResponse
     */
    function response(array $errors): JsonResponse
    {
        return new JsonResponse($errors, 400);
    }

    /**
     * Customize failed authorization.
     */
    protected function failedAuthorization()
    {
        abort(401, message('auth.unauthorized'));
    }
}
