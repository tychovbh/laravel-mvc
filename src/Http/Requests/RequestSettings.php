<?php
namespace Tychovbh\Mvc\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

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

    /**
     * Set messages
     * @return array
     */
    public function messages(): array
    {
        $messages = [];
        foreach ($this->rules() as $field => $rules)
        {
            $rules = explode('|', $rules);

            foreach ($rules as $rule) {
                $rule = explode(':', $rule);
                switch ($rule[0]) {
                    case 'required_with':
                        $message = message('field.' . $rule[0], $this->translate($field));
                        break;
                    case 'min':
                        $message = message('field.' . $rule[0], $field, $this->translate($rule[1]));
                        break;
                    default:
                        $message = message('field.' . $rule[0], $this->translate($field));

                }
                $messages[$field . '.' . $rule[0]] = $message;
            }
        }

        return $messages;
    }

    /**
     * Translate fields
     * @param string $field
     * @return string
     */
    private function translate(string $field): string
    {
        if (!method_exists($this, 'translations')) {
            return $field;
        }

        $translations = $this->translations();

        return Arr::get($translations, $field) ?? $field;
    }
}
