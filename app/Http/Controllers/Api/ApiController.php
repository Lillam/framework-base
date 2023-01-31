<?php

namespace App\Http\Controllers\Api;

use Vyui\Foundation\Http\Controller;

class ApiController extends Controller
{
    /**
     * @var array|string[]
     */
    protected array $middleware = [
        \Vyui\Foundation\Http\Middleware\Authenticate::class
    ];
}