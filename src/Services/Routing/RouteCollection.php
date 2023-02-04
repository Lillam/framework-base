<?php

namespace Vyui\Services\Routing;

use Closure;
use Vyui\Foundation\Http\Request;

class RouteCollection
{
    /**
     * @var array<string, Route[]>
     */
    protected array $routes;

    /**
     * @param string $method
     * @param string $uri
     * @param string|array|Closure $action
     * @return void
     */
    public function set(string $method, string $uri, string|array|Closure $action): void
    {
        $this->routes[strtoupper($method)][$uri] = new Route($method, $uri, $action);
    }

    /**
     * @param string $method
     * @return Route[]
     */
    public function get(string $method): array
    {
        return $this->routes[strtoupper($method)] ?? [];
    }

    /**
     * @return Route[]
     */
    public function all(): array
    {
        return $this->routes;
    }

    /**
     * Match the request against a route we have in the system.
     *
     * @param Request $request
     * @return Route|null
     */
    public function find(Request $request): ?Route
    {
        foreach ($this->get($request->getMethod()) as $route) {
            if ($route->isMatching($request)) {
                return $route;
            }
        }

        return null;
    }
}