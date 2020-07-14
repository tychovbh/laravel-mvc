<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Requests;
use Tychovbh\Mvc\Http\Requests\Lumen\FormRequest;

class UpdateRole extends FormRequest
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
            'name' => 'string|min:1',
            'label' => 'string|min:1',
            'users' => 'array',
        ];
    }
}
