<?php

namespace Tychovbh\Mvc\Http\Requests;

use Tychovbh\Mvc\Http\Requests\Lumen\FormRequest;

class StorePasswordReset extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|string|email|exists:users,email'
        ];
    }
}
