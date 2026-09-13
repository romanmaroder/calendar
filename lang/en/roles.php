<?php

return [
    'index' => [
        'title' => 'Roles',
        'table' => [
            'id' => 'ID',
            'name' => 'Name',
            'permissions' => 'Permissions',
            'actions' => 'Actions'
        ],
    ],
    'create' => [
        'title' => 'Create Role',
        'placeholder' => [
            'name'=>'Role name',
            'permissions'=>'Select permissions'
        ]
    ],
    'update' => [
        'title'=>'Update role'
    ]
];