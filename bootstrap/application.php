<?php

/*
|-----------------------------------------------------------------------------------------------------------------------
| Instantiate the Application.
|-----------------------------------------------------------------------------------------------------------------------
| The first thing the application needs to do, is boot itself up, so here we're going to make an application instance
| which will act as the overarching container for everything that is pertinent to the application.
|
*/

$application = new Vyui\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|-----------------------------------------------------------------------------------------------------------------------
| Bind the necessary Instances.
|-----------------------------------------------------------------------------------------------------------------------
| The first thing the application needs to do, is boot itself up, so here we're going to make an application instance
| which will act as the overarching container for everything that is pertinent to the application.
|
*/

$application->singleton(
    Vyui\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$application->singleton(
    Vyui\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|-----------------------------------------------------------------------------------------------------------------------
| Return the Application.
|-----------------------------------------------------------------------------------------------------------------------
| Any script requiring this script, will be requiring the application instance from it, to which, we're going to want
| return the application upon being instantiated and bootstrapped with the necessary core components.
|
*/

return $application;