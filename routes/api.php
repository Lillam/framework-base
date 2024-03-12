<?php

/*
|-----------------------------------------------------------------------------------------------------------------------
| Application's Api Routes.
|-----------------------------------------------------------------------------------------------------------------------
| Define all of your applications web/api accessed routes, all these routes are loaded via the RoutingService and are
| grouped accordingly to the file name. You can begin adding routes in two ways, one of which is using the Route Facade
| the other is The router itself which is inside the application.
|
| $router = Vyui\Foundation\Container\Container::getInstance()->make(Vyui\Routing\Routing::class);
| $router->get('/', 'Apicontroller@method');
|
*/

use Vyui\Services\Routing\Router;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\UserController;

/** @var Router $router */
$router = app(Router::class);

$router->group("/api/v1", function (Router $router) {
    $router->get("/testing", function () {
        die("here again");
    });

    $router->group("/token", function (Router $router) {
        $router->get("", [ApiController::class, "getToken"]);
        $router->get("/refresh", [ApiController::class, "refreshToken"]);
    });

    $router->get("/users", [UserController::class, "index"]);
    $router->post("/login", [UserController::class, "login"]);
});
