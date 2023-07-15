<?php

return [
    'login' => env('IIKO_LOGIN'),
    'password' => env('IIKO_PASSWORD'),
    'url' => [
        'base' => 'https://[]',
        'auth' => '/resto/api/auth',
        'corporation' => [
            'departments' => '/resto/api/corporation/departments',
            'stores' => '/resto/api/corporation/stores',
        ],
        'entities' => [
            'products' => [
                'list' => '/resto/api/v2/entities/products/list',
            ],
        ],
    ],
];
