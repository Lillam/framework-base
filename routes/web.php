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
use App\Http\Controllers\Api\ApiController;

/** @var Router $router */
$router = app(Router::class);

$router->get('/yascreem/{test?}', fn ($test) => view('home3', ['test' => $test]));

$router->group('/api/v1', function (Router $router) {
    $router->get('/testing', function () {
        die('here');
    });

    $router->group('/token', function (Router $router) {
        $router->get('', [ApiController::class, 'getToken']);
        $router->get('/refresh', [ApiController::class, 'refreshToken']);
    });

    $router->get('/users', [\App\Http\Controllers\Api\UserController::class, 'index']);
    $router->post('/login', [\App\Http\Controllers\Api\UserController::class, 'login']);
});

// Route::get('/yascreem/{test?}', fn ($test) => view('home2', ['test' => $test]));
Route::get('/tests/{test}/{testing}', [\App\Http\Controllers\Web\WebController::class, 'test']);

Route::get('/', [WebController::class, 'index']);
Route::get('/tasks/{task?}', [\App\Http\Controllers\Web\WebController::class, 'index']);

Route::get('/parseToken', [WebController::class, 'parseToken']);
