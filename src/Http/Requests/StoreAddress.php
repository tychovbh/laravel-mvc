<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Requests;

use Tychovbh\Mvc\Http\Requests\Lumen\FormRequest;

class StoreAddress extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**v
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules(): array
    {
        return [
            'zipcode' => 'required|string|min:6',
            'house_number' => 'required|integer'
        ];
    }
}

