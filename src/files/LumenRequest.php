<?php
declare(strict_types=1);

namespace App\Http\Requests;
use Tychovbh\Mvc\Http\Requests\Lumen\FormRequest;

class EntityRequest extends FormRequest
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
            //
        ];
    }

    /**
     * Get field translations for messages.
     * @return array
     */
    public function translations(): array
    {
        return [
            //
        ];
    }
}

