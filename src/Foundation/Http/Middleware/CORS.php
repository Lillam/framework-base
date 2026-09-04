<?php

namespace Vyui\Foundation\Http\Middleware;

use Closure;
use Vyui\Foundation\Http\{Request, Response};

class CORS extends Middleware
{
    public function handle(Request $request, Closure $next): Response
    {
        print("Cors was processed...");

        return $next($request);
    }
}