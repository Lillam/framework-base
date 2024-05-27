<?php

/*
|-----------------------------------------------------------------------------------------------------------------------
| Application's Web Routes.
|-----------------------------------------------------------------------------------------------------------------------
| Define all of your applications web accessed routes, all these routes are loaded via the RoutingService and are
| grouped accordingly to the file name. You can begin adding routes in two ways, one of which is using the Route Facade
| the other is The router itself which is inside the application.
|
| $router = Vyui\Foundation\Container\Container::getInstance()->make(Vyui\Routing\Routing::class);
| $router->get('/', 'WebController@method');
|
*/

use Vyui\Support\Facades\Route;
use Vyui\Services\Routing\Router;
use App\Http\Controllers\Web\WebController;

router()->get('/test', fn() => view(
    'home',
    ['d1' => 1, 'd2' => 2, 'd3' => 3]
));

// /** @var Router $router */
// $router = app(Router::class);

// $router->get("/yascreem/{test?}", fn($test) => view("home3", ["test" => $test]));

// // Route::get('/yascreem/{test?}', fn ($test) => view('home2', ['test' => $test]));
// Route::get("/tests/{test}/{testing}", [\App\Http\Controllers\Web\WebController::class, "test"]);

// Route::get("/", [WebController::class, "index"]);
// Route::get("/tasks/{task?}", [\App\Http\Controllers\Web\WebController::class, "index"]);

// Route::get("/parseToken", [WebController::class, "parseToken"]);
