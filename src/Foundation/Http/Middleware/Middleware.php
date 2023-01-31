<?php

namespace Vyui\Foundation\Http\Middleware;

use Vyui\Foundation\Http\Request;

abstract class Middleware
{
    /**
     * @param Request $request
     * @return void
     */
    abstract public function handle(Request $request): void;
}