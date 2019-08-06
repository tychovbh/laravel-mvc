<?php
declare(strict_types=1);

return [
    'model' => [
        'notfound' => '%s not found with %s: %s',
        'invalid' => sprintf('The request is invalid! Please contact %s.', config('app.support.email')),
    ],
    'field' => [
        'required' => 'Input field %s is missing from the Request.',
        'required_with' => 'Input field %s is missing from the Request.',
        'required_without' => 'Input field %s is missing from the Request.',
        'min' => 'Input field %s should have a minimum value of %s.',
        'email' => 'E-mailadres is niet geldig.',
        'exists' => '%s with given ID does not exists.',
        'unique' => '%s already taken.',
        'integer' => '%s is not a valid Number.',
        'string' => '%s is not a valid Text.',
        'date' => '%s is not a valid date.',
    ],
    'server' => [
        'error' => sprintf('Server error! Please contact %s.', config('app.support.email')),
    ],
    'auth' => [
        'unauthorized' => 'Unauthorized!'
    ]
];
