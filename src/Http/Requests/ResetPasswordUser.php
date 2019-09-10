<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Requests;

use Tychovbh\Mvc\Http\Requests\Lumen\FormRequest;

class ResetPasswordUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules(): array
    {
        return [
            'token' => 'required|exists:password_resets,token'
        ];
    }
}

