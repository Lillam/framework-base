<?php

namespace Vyui\Support\Facades;

use Vyui\Services\Facades\Facade;
use Vyui\Contracts\Database\ConnectionManager;

/**
 * @method static connection(string $connection): Connection
 * @method static getConnection(?string $connection): Connection
 * @method static getConnections(): Connection[]
 */
class DB extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return ConnectionManager::class;
    }
}