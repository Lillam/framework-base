<?php

namespace Vyui\Services\Routing;

use Closure;
use Exception;
use Vyui\Foundation\Application;
use Vyui\Foundation\Http\Request;
use Vyui\Foundation\Http\Response;
use Vyui\Services\Routing\RouteNotFoundException;

class Router
{
    /**
     * The containing application.
     *
     * @var Application
     */
    protected Application $application;

    /**
     * @var RouteCollection
     */
    protected RouteCollection $routes;

    /**
     * @var string
     */
    protected string $groupUri = '';

    /**
     * The current existing route.
     *
     * @var Route|null
     */
    protected ?Route $current = null;

    /**
     * @param Application $application
     * @param RouteCollection $routes
     */
    public function __construct(Application $application, RouteCollection $routes)
    {
        $this->application = $application;
        $this->routes = $routes;
    }

    /**
     * @param string $uri
     * @param Closure $closure
     * @return $this
     */
    public function group(string $uri, Closure $closure): self
    {
        $this->groupUri .= $uri;

        $closure($this);

        // unset the group uri that has been set for this particular grouping instance.
        $this->groupUri = str_replace($uri, '', $this->groupUri);

        return $this;
    }

    /**
     * @param string $uri
     * @param string|array|Closure $action
     * @return self
     */
    public function get(string $uri, string|array|Closure $action): self
    {
        $this->routes->set('GET', "{$this->groupUri}{$uri}", $action);

        return $this;
    }

    /**
     * @param string $uri
     * @param string|array|Closure $action
     * @return self
     */
    public function post(string $uri, string|array|Closure $action): self
    {
        $this->routes->set('POST', "{$this->groupUri}{$uri}", $action);

        return $this;
    }

    /**
     * @param string $uri
     * @param string|array|Closure $action
     * @return self
     */
    public function put(string $uri, string|array|Closure $action): self
    {
        $this->routes->set('PUT', "{$this->groupUri}{$uri}", $action);

        return $this;
    }

    /**
     * @param string $uri
     * @param string|array|Closure $action
     * @return void
     */
    public function patch(string $uri, string|array|Closure $action): void
    {
        $this->routes->set('PATCH', "{$this->groupUri}{$uri}", $action);
    }

    /**
     * @param string $uri
     * @param string|array|Closure $action
     * @return void
     */
    public function delete(string $uri, string|array|Closure $action): void
    {
        $this->routes->set('DELETE', "{$this->groupUri}{$uri}", $action);
    }

    /**
     * Check whether or not the current uri route matches the route.
     *
     * @param string $uri
     * @param string|array|Closure $action
     * @return void
     */
    public function match(string $uri, string|array|Closure $action): void
    {
        foreach (['GET', 'POST'] as $method) {
            $this->routes->set($method, $uri, $action);
        }
    }

    /**
     * @param string $uri
     * @param string|array|Closure $action
     * @return void
     */
    public function all(string $uri, string|array|Closure $action): void
    {
        foreach (['GET', 'POST', 'PUT', 'PATCH', 'DELETE'] as $method) {
            $this->routes->set($method, $uri, $action);
        }
    }

    /**
     * Get specific method routes or all routes if you omit the method.
     * omitting the method would return all routes that are bounded to the
     * router with their method as the key
     * * GET => [
     *     '/test' => [controller, method]
     * ]
     * ... rest
     *
     * passing a method will return all routes for that method.
     * ['/test' => [controller, method]]
     *
     * @return array<Route[]>|Route[]
     */
    public function routes(?string $method = null): array
    {
        if ($method !== null) {
            return $this->routes->get(strtoupper($method)) ?? [];
        }

        return $this->routes->all();
    }

    /**
     * Dispatch the current route.
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function dispatch(Request $request): Response
    {
        if (! $route = $this->findRoute($request)) {
            throw new RouteNotFoundException("The route was not found");
        }

        return $route->dispatch($this->application, $request);
    }

    /**
     * Find the particular route that matches the current existing request.
     *
     * @param Request $request
     * @return Route|null
     */
    private function findRoute(Request $request): ?Route
    {
        return $this->current = $this->routes->find($request);
    }

    /**
     * @param string $uri
     * @param bool $replace
     * @param int $code
     * @return void
     */
    public function redirect(string $uri, bool $replace = true, int $code = 301): void
    {
        header("Location: $uri", $replace, $code);
        exit;
    }
}
