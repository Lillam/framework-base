<?php

namespace Vyui\Foundation\Http\Middleware;

use Vyui\Foundation\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Handle the authentication middleware; which will check for a variety of things 
     * depending on the type of auth that we're going for.
     *
     * @param Request $request
     * @return Request
     */
    public function handle(Request $request): Request
    {    
        // token based authorization
        if ($request->get("token")) {
            
        }

        // header based authorization
        if ($request->header("http_authorization")) {
            
        }

        return $request;
    }
}
