<?php

namespace Vyui\Support\Facades;

use Vyui\Services\Facades\Facade;
use Vyui\Services\Config\ConfigService;

/**
 * @method static ConfigService get(string $keys, mixed $default = null): mixed
 * 
 * @see ConfigService
 */
class Config extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return ConfigService::class;
    }
}