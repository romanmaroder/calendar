<?php

return [
    'index' => [
        'title' => 'Роли',
        'table' => [
            'id' => 'ID',
            'name' => 'Название',
            'permissions' => 'Разрешения',
            'actions' => 'Действия'
        ],
    ],
    'create' => [
        'title' => 'Создать роль',
        'placeholder' => [
            'name'=>'Название роли',
            'permissions'=>'Выберите разрешения'
        ]
    ],
    'update' => [
        'title'=>'Обновить роль'
    ]
];