<?php

namespace Vyui\Support\Facades;

use Vyui\Services\Facades\Facade;
use Vyui\Services\Events\Dispatcher;

class Job extends Facade
{
    /**
     * @method static Dispatcher dispatch(string|object $event, mixed $payload = null): object | string
     */
    public static function getFacadeAccessor(): string
    {
        return Dispatcher::class;
    }
}