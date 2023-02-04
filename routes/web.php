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

//Route::get('/yascreem/{test}', function (): \Vyui\Foundation\Http\Response {
//    return view('home2');
//});

Route::get('/yascreem/{test?}', fn ($test) => view('home2', ['test' => $test]));

Route::get('/', [WebController::class, 'index']);
Route::get('/tests/{test}/{testing}', [\App\Http\Controllers\Web\WebController::class, 'test']);
Route::get('/tasks/{task?}', [\App\Http\Controllers\Web\WebController::class, 'index']);

Route::get('/api/users', [\App\Http\Controllers\Api\UserController::class, 'index']);
Route::post('/api/login', [\App\Http\Controllers\Api\UserController::class, 'login']);