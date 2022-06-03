<?php

return [

	/*
	| -----------------------------------------------------------------------------------------------------------------
	| Application Name
	| -----------------------------------------------------------------------------------------------------------------
	|
	| This value is the name of your Application. This value is used when the framework needs to place the
	| application's name anywhere suc has notifications or any other location as required by the application and or
	| packages.
	|
	*/

    'name' => env('APP_NAME', 'VYUI'),

	/*
	| -----------------------------------------------------------------------------------------------------------------
	| Application Environment
	| -----------------------------------------------------------------------------------------------------------------
	|
	| This value is the "environment" of your application, which will be limited to various states. depending on your
	| environment variable will depend on the use-ability of other services that reside within the application.
	|
	*/

	'env' => env('APP_ENV', 'production'),

	/*
	| -----------------------------------------------------------------------------------------------------------------
	| Application Languages
	| -----------------------------------------------------------------------------------------------------------------
	|
	| This value is the default language of the application. This value will decide what language to utilise by default
	| when using the translation feature of the framework. as well as any other service that the framework may depend
	| on this value.
	|
	*/

    'language' => [
        'default' => env('APP_LANGUAGE', 'en'),
        'location' => __DIR__ . '../resources/languages/',
    ]

];