<?php

return [
	'default' => env('DB_CONNECTION', 'mysql'),

	'connections' => [
		'mysql' => [
			'host' => env('DB_HOST', '127.0.0.1'),
			'port' => env('DB_PORT', '3306'),
			'database' => env('DB_DATABASE', 'admin'),
			'username' => env('DB_USERNAME', 'admin'),
			'password' => env('DB_PASSWORD', ''),
		],

		'sqlite' => [
			'path' => env('DB_PATH')
		]
	]
];