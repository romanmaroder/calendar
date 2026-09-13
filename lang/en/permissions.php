<?php

return [
    'index' => [
        'title' => 'Permissions',
        'message' => '«Configure create and edit permissions via seed files only manually editing rights may break access control and cause authorization errors.»',
        'table' => [
            'id' => 'ID',
            'name' => 'Name',
            'guard' => 'Guard',
            'actions' => 'Actions'
        ],
    ],
    'create' => [
        'title' => 'Create permission',
        'message' => '«Compare names with the seed file before creating.»',
        'placeholder' => [
            'name' => 'Permission name',
        ],
        'toast' => [
            'create' => 'Create',
            'update' => 'Update',
        ]
    ],
    'update' => [
        'title' => 'Update permission',
        'message' => '«Compare names with the seed file before editing.»',
        'toast' => [
            'create' => 'Permission create',
            'update' => 'Permission update',
        ]
    ],
];