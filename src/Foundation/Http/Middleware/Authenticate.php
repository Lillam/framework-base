<?php

namespace Vyui\Foundation\Http\Middleware;

use Closure;
use Vyui\Foundation\Http\{Request, Response};

class Authenticate extends Middleware
{
    /**
     * Handle the authentication middleware; which will check for a variety of things 
     * depending on the type of auth that we're going for.
     *
     * @param Request $request
     * @param Closure(Request): Response $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {    
        print("authenticate has processed");

        // token based authorization
        if ($request->get("token")) {
            
        }

        // header based authorization
        if ($request->header("http_authorization")) {
            
        }

        return $next($request);
    }
}
