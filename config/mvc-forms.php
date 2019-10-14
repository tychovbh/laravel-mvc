<?php

return [
    'elements' => [
        [
            'label' => 'Input Field',
            'name' => 'input',
            'description' => 'Create an Input Field',
            'properties' => ['name', 'id', 'placeholder', 'type', 'default', 'alt'],
        ],
        [
            'label' => 'Dropdown Field',
            'name' => 'select',
            'description' => 'Create a list of options.',
            'properties' => ['name', 'id', 'default', 'options', 'source', 'label_key', 'value_key'],
        ],
        [
            'label' => 'Text Area Field',
            'name' => 'textarea',
            'description' => 'Create a Text Area Field.',
            'properties' => ['name', 'id', 'placeholder', 'default'],
        ],
        [
            'label' => 'Searchable Field',
            'name' => 'autocomplete',
            'description' => 'Create a searchable Input Field',
            'properties' => ['name', 'id', 'placeholder', 'default', 'route'],
        ],
        [
            'label' => 'Rich Text Editor Field',
            'name' => 'wysiwyg',
            'description' => 'Create a Rich Text Editor Field',
            'properties' => ['name', 'id', 'placeholder', 'default'],
        ],
        [
            'label' => 'Dropzone Upload Field',
            'name' => 'dropzone',
            'description' => 'Create a File upload with drag and drop',
            'properties' => ['name', 'id', 'placeholder', 'type', 'default'],
        ],
    ],
    'properties' => [
        [
            'name' => 'name',
            'label' => 'Field name',
            'options' => null,
        ],
        [
            'name' => 'placeholder',
            'label' => 'Field placeholder',
            'options' => null,
        ],
        [
            'name' => 'type',
            'label' => 'Field type',
            'options' => ['text', 'number', 'email', 'password', 'file', 'checkbox', 'radio'],
        ],
        [
            'name' => 'default',
            'label' => 'Field default value.',
            'options' => null,
        ],
        [
            'name' => 'id',
            'label' => 'Field ID',
            'options' => null,
        ],
        [
            'name' => 'options',
            'label' => 'Field options',
            'options' => null,
        ],
        [
            'name' => 'route',
            'label' => 'Route endpoint',
            'options' => null,
        ],
        [
            'name' => 'hidden',
            'label' => 'Not visible',
            'options' => null,
        ],
        [
            'name' => 'alt',
            'label' => 'Alt value',
            'options' => null,
        ],
        [
            'name' => 'source',
            'label' => 'Source',
            'options' => null,
        ],
        [
            'name' => 'label_key',
            'label' => 'Label Key',
            'options' => null,
        ],
        [
            'name' => 'value_key',
            'label' => 'Value Key',
            'options' => null,
        ],
        [
            'name' => 'data_key',
            'label' => 'Data Key',
            'options' => null,
        ],
    ],
    'forms' => [
        [
            'name' => 'users',
            'fields' => [
                [
                    'element' => 'input',
                    'properties' => ['name' => 'email', 'type' => 'email', 'required' => true, 'placeholder' => 'test@example.com'],
                ],
                [
                    'element' => 'input',
                    'properties' => ['name' => 'password', 'type' => 'password', 'required' => true],
                ],
                [
                    'element' => 'input',
                    'properties' => ['name' => 'name', 'type' => 'text', 'required' => true],
                ],
                [
                    'element' => 'input',
                    'properties' => ['name' => 'avatar', 'type' => 'file'],
                ],
                [
                    'element' => 'select',
                    'properties' => ['name' => 'role_id', 'options' => [], 'source' => 'roles.index', 'label_key' => 'label', 'value_key' => 'id'],
                ],
            ]
        ],
    ]
];
