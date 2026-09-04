<?php

namespace Vyui\Foundation\Http\Middleware;

use Closure;
use Vyui\Foundation\Http\{Request, Response};

abstract class Middleware
{
    /**
     * Handle the middleware. appropriately and then upon the request being handled
     * return the request that has been handled... this allows the request to be
     * modified and passed around from middleware to middleware until it reaches
     * the client.
     *
     * @param Request $request
     * @return Response
     */
    abstract public function handle(Request $request, Closure $next): Response;
}
