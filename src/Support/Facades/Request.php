<?php

namespace Vyui\Support\Facades;

use Vyui\Services\Facades\Facade;
use Vyui\Foundation\Http\Request as HttpRequest;

/**
 * @method static HttpRequest method(): string
 */
class Request extends Facade
{
    /**
     *  Get the Request Facade accessor name.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return HttpRequest::class;
    }
}