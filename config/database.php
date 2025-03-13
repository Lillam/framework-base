<?php

return [
    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
        'mysql' => [
            'host'     => env('DB_HOST', '127.0.0.1'),
            'port'     => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'root'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
        ],

        'sqlite' => [
            'path' => env('DB_PATH')
        ]
    ]
];
