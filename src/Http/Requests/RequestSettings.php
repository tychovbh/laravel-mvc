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

    /**
     * Generate messages from rules
     * @param array $validation
     * @param array $messages
     * @param string|null $current_field
     * @return array
     */
    private function messagesFromRules(array $validation, array $messages, $current_field = null)
    {
        foreach ($validation as $key => $rules)
        {
            $field = $current_field ?? $key;
            if (is_array($rules)) {
                $messages = $this->messagesFromRules($rules, $messages, $field);
                continue;
            }

            if (is_a($rules, Rule::class)) {
                continue;
            } else {
                $rules = explode('|', $rules);
            }

            foreach ($rules as $rule) {
                $rule = explode(':', $rule);
                switch ($rule[0]) {
                    case 'required_with':
                        $message = message('field.' . $rule[0], __($field));
                        break;
                    case 'min':
                    case 'before':
                    case 'after':
                    case 'mimes':
                        $message = message('field.' . $rule[0], __($field), __($rule[1]));
                        break;
                    default:
                        $message = message('field.' . $rule[0], __($field));

                }
                $messages[$field . '.' . $rule[0]] = $message;
            }
        }

        return $messages;
    }

    /**
     * Set messages
     * @return array
     */
    public function messages(): array
    {
        return $this->messagesFromRules($this->rules(), []);
    }
}
