<?php

namespace Vyui\Foundation\Http\Middleware;

use Vyui\Foundation\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Handle the authentication middleware; which will check for a variety of things depending on the type of auth
     * that we're going for.
     *
     * @param Request $request
     * @return void;
     */
    public function handle(Request $request): void
    {
        // token based authorization
        if ($request->get("token")) {
            var_dump($request->get("token"));
        }

        // beader based authorization
        if ($request->getHeader("http_authorization")) {
            var_dump($request->getHeader("http_authorization"));
        }
    }
}
