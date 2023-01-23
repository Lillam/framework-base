<?php

namespace Vyui\Services\Routing;

use Closure;
use Exception;
use Vyui\Foundation\Application;
use Vyui\Foundation\Http\Request;
use Vyui\Foundation\Http\Response;
use Vyui\Exceptions\Routing\RouteNotFoundException;

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
     * @param string|array|Closure $action
     * @return self
     */
    public function get(string $uri, string|array|Closure $action): self
    {
		$this->routes->set('GET', $uri, $action);

		return $this;
    }

    /**
     * @param string $uri
     * @param string|array|Closure $action
     * @return self
     */
    public function post(string $uri, string|array|Closure $action): self
    {
		$this->routes->set('POST', $uri, $action);

		return $this;
    }

    /**
     * @param string $uri
     * @param string|array|Closure $action
     * @return self
     */
    public function put(string $uri, string|array|Closure $action): self
    {
		$this->routes->set('PUT', $uri, $action);

		return $this;
    }

    /**
     * @param string $uri
     * @param string|array|Closure $action
     * @return void
     */
    public function patch(string $uri, string|array|Closure $action): void
    {
		$this->routes->set('PATCH', $uri, $action);
    }

    /**
     * @param string $uri
     * @param string|array|Closure $action
     * @return void
     */
    public function delete(string $uri, string|array|Closure $action): void
    {
        $this->routes->set('DELETE', $uri, $action);
    }

    /**
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
     * Get all routes that are bound to the router.
     *
     * @return Route[]
     */
    public function allRoutes(): array
    {
        return $this->routes->all();
    }

	/**
	 * Get all get routes that are bound to the router.
	 *
	 * @return Route[]
	 */
	public function getRoutes(): array
	{
		return $this->routes->get('GET') ?? [];
	}

    /**
     * Get all post routes that are bound to the router.
     *
     * @return Route[]
     */
    public function postRoutes(): array
    {
        return $this->routes->get('POST') ?? [];
    }

    /**
     * Get all patch routes that are bound to the router.
     *
     * @return Route[]
     */
    public function patchRoutes(): array
    {
        return $this->routes->get('PATCH') ?? [];
    }

    /**
     * Get all delete routes that are bound to the router.
     *
     * @return Route[]
     */
    public function deleteRoutes(): array
    {
        return $this->routes->get('DELETE') ?? [];
    }

    /**
     * Get all put routes that are bound to the router.
     *
     * @return Route[]
     */
    public function putRoutes(): array
    {
        return $this->routes->get('PUT') ?? [];
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