<?php

namespace Tychovbh\Mvc\Http\Requests\Laravel;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Illuminate\Validation\ValidationException;
use Tychovbh\Mvc\Http\Requests\RequestSettings;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class FormRequest extends BaseFormRequest
{
    use RequestSettings;


    /**
     * Handle a failed validation attempt.
     *
     * @param  Validator  $validator
     * @return void
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json(['errors' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
