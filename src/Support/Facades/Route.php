<?php

namespace Vyui\Support\Facades;

use Closure;
use Vyui\Services\Facades\Facade;
use Vyui\Services\Routing\Router;

/**
 * @method static Router get(string $uri, array|string|Closure $action): void
 * @method static Router post(string $uri, array|string|Closure $action): void
 * @method static Router put(string $uri, array|string|Closure $action): void
 * @method static Router patch(string $uri, array|string|Closure $action): void
 * @method static Router delete(string $uri, array|string|Closure $action): void
 * @method static Router all(string $uri, array|string|Closure $action): void
 * @method static Router allRoutes(): Route[]
 * @method static Router getRoutes(): Route[]
 * @method static Router postRoutes(): Route[]
 * @method static Router putRoutes(): Route[]
 * @method static Router patchRoutes(): Route[]
 * @method static Router deleteRoutes(): Route[]
 * @method static Router redirect(string $uri, bool $replace, int $code): void
 * @see Router
 */
class Route extends Facade
{
    /**
     * Get the Route Facade accessor name.
     *
     * @return string
     */
    public static function getFacadeAccessor(): string
    {
        return Router::class;
    }
}