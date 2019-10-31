<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Requests;
use Tychovbh\Mvc\Http\Requests\Lumen\FormRequest;

class StoreUser extends FormRequest
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
            'name' => 'required_with:token|string|min:1',
            'email' => 'required_without:token|string|email|unique:users,email',
            'token' => 'required_without:email|string',
            'password' => 'min:8'
        ];
    }
}
