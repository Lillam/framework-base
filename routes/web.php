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
use App\Http\Controllers\Web\WebController;
use App\Http\Controllers\Api\UserController;

router()->get('/test', fn() => view(
    'home3',
    ['d1' => 1, 'd2' => 2, 'd3' => 3]
));

Route::get('/', [WebController::class, 'home']);

Route::get("/giveToken", [WebController::class, 'giveToken']);
Route::get("/parseToken", [WebController::class, "parseToken"]);

Route::get('/random', [UserController::class, 'random']);
