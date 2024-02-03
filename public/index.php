<?php

use Vyui\Contracts\Http\Kernel;
use Vyui\Foundation\Application;
use Vyui\Foundation\Http\Request;

define("FRAMEWORK_START", microtime(true));

/*
|-----------------------------------------------------------------------------------------------------------------------
| Fire up the Autoloader.
|-----------------------------------------------------------------------------------------------------------------------
| Register the autoloader that composer provides, conveniently which automatically generates a class loader for this
| Application. All we need to do from here on out is just simply require the autoloader, and never need to manually
| register classes.
|
*/

require '../vendor/autoload.php';

/*
|-----------------------------------------------------------------------------------------------------------------------
| Fire up the Application.
|-----------------------------------------------------------------------------------------------------------------------
| Bootstrap and return the application to the main script, this will render everything that the application needs in
| order to fully function and operate as an application.
|
*/

/** @var Application $application */
$application = require_once '../bootstrap/application.php';

/*
|-----------------------------------------------------------------------------------------------------------------------
| Layout the play area.
|-----------------------------------------------------------------------------------------------------------------------
| Offer a playground to fool around and test some random code whilst in the mode of building the application. Take a
| break from writing tests and calm down and let your inner playful side out.
|
*/

if (env('APP_ENV') === 'dev') {
    require_once './playground.php';
}

/*
|-----------------------------------------------------------------------------------------------------------------------
| Make the Http Kernel.
|-----------------------------------------------------------------------------------------------------------------------
| Allow the application to fire up a kernel for handling all incoming requests and then binding the application to the
| router and allowing the two to come together and form a connection among the app, the router and the request.
|
*/

/** @var \App\Http\Kernel $kernel */
$kernel = $application->make(Kernel::class);

/*s
|-----------------------------------------------------------------------------------------------------------------------
| Prepare and Ship the Request into a Response.
|-----------------------------------------------------------------------------------------------------------------------
| Capture the request that the user has made and then ship this request off to the kernel so that the response can be
| prepared and returned to the handling the process of building out a response
| to show.
|
*/

$response = $kernel->handle($request = Request::capture());

/*
|-----------------------------------------------------------------------------------------------------------------------
| Send the response.
|-----------------------------------------------------------------------------------------------------------------------
| after the previous step of acquiring the request, we're going to want to send the request and allow the user to see
| the fruits of what had been prepared. This is the final step that the user will see; upon this happening nothing else
| will be visible to the user, any user content code will want to be above this portion of code, as opposed to below it.
|
*/

$response->send();

/*
|-----------------------------------------------------------------------------------------------------------------------
| Close the Doors.
|-----------------------------------------------------------------------------------------------------------------------
| Terminate everything to do with the application and perform some clean up. here we're going to be terminating the
| response as well as request, and anything that needs to happen during the teardown will happen here also.
|
*/

// todo - setup some terminating  callbacks that the application will need in order to begin cleaning up everything that
//        the application may or may not need to do.
