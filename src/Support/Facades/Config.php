<?php

namespace Vyui\Support\Facades;

use Vyui\Services\Facades\Facade;
use Vyui\Services\Config\ConfigService;

/**
 * This facade has special static methods that you can access specific files with 
 * the configuration, for example, if you have a file config/app.php and config/database.php 
 * then you can run the following in order to get information out of those configuration
 * files:
 * 
 * Config::app(keys: 'APP_ENV', default: 'dev')
 * Config::database(keys: 'HOST', default: '127.0.0.1')
 * 
 * and this will acquire from the specific config. 
 * 
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