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
| Bind the Applications Kernel.
|-----------------------------------------------------------------------------------------------------------------------
| The application is in need of something that will be taking care of all the hits to the index page, first things first
| we are going to be applying the http kernel to the application so that all requests will be handled by this.
|
*/

$application->singleton(Vyui\Contracts\Http\Kernel::class, App\Http\Kernel::class);

/*
|-----------------------------------------------------------------------------------------------------------------------
| Bind the Console Kernel.
|-----------------------------------------------------------------------------------------------------------------------
| The Console is in need of something that will be taking care of all the hits that are to the console (conjure) file
| and processing the outcome.
|
*/

$application->singleton(Vyui\Contracts\Console\Kernel::class, App\Console\Kernel::class);

/*
|-----------------------------------------------------------------------------------------------------------------------
| Return the Application.
|-----------------------------------------------------------------------------------------------------------------------
| Any script requiring this script, will be requiring the application instance from it, to which, we're going to want
| return the application upon being instantiated and bootstrapped with the necessary core components.
|
*/

return $application;
