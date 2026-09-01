<?php

namespace Vyui\Support\Facades;

use Vyui\Services\Facades\Facade;
use Vyui\Services\Environment\EnvironmentService;

/**
 * @method static EnvironmentService get(string $key, mixed $defaukt = null): mixed
 * @see EnvironmentService
 */
class Env extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return EnvironmentService::class;
    }
}