<?php

namespace Vyui\Services\Routing;

use Closure;
use Vyui\Support\_String;
use Vyui\Support\_Reflect;
use Vyui\Foundation\Application;
use Vyui\Foundation\Http\Request;
use Vyui\Foundation\Http\Response;

class Route
{
    /**
     * The uri that has been defined.
     *
     * @var string
     */
    protected string $uri;

	/**
	 * The regex rendition of the uri that has been defined.
	 *
	 * @var RouteUriRegex
	 */
	protected RouteUriRegex $uriRegex;

	/**
	 * @var string
	 */
	protected string $method;

    /**
     * The handler of which will be called upon being handled.
     *
	 * [controller, action]
     * @var array
     */
    protected array $action;

	/**
	 * @var RouteParameters
	 */
	// protected RouteParameters $routeParameters;

	/**
	 * @var array
	 */
	protected array $parameters = [];

    /**
	 * @param string $method
     * @param string $uri
     * @param string|array|Closure $action
     */
    public function __construct(string $method, string $uri, string|array|Closure $action)
    {
		$this->setMethod($method)
			 ->setUri($uri)
			 ->setAction($action);

		$this->uriRegex = new RouteUriRegex($this);
		// $this->routeParameters = new RouteParameters($this);
    }

	/**
	 * et the method assigned to this particular route.
	 *
	 * @param string $method
	 * @return $this
	 */
	public function setMethod(string $method): self
	{
		$this->method = $method;

		return $this;
	}

	/**
	 * Get the method assigned to this particular route.
	 *
	 * @return string
	 */
	public function getMethod(): string
	{
		return $this->method;
	}

	/**
	 * Set the uri of this particular route.
	 *
	 * @param string $uri
	 * @return $this
	 */
	public function setUri(string $uri): self
	{
		$this->uri = $uri;

		return $this;
	}

	/**
	 * Get the url string of this particular route.
	 *
	 * @return string
	 */
	public function getUri(): string
	{
		return $this->uri;
	}

	/**
	 * @return RouteUriRegex
	 */
	public function getUriRegex(): RouteUriRegex
	{
		return $this->uriRegex;
	}

	/**
	 * Get a normalised version of the url string of this particular route.
	 *
	 * @return string
	 */
	public function getNormalisedUri(): string
	{
		return preg_replace(
			'/[\/]{2,}/',
			'',
			'/' . trim($this->getUri(), '/') . '/'
		);
	}

	/**
	 * Set the action of this particular route.
	 *
	 * @param string|array|Closure $action
	 * @return $this
	 */
	public function setAction(string|array|Closure $action): self
	{
		$this->action = match (gettype($action)) {
			'array' => $action,
			'string' => explode('@', $action),
			'object' => [null, $action],
			default => null,
		};

		if (! $this->action) {
			// throw an error here... we're needing to break out, that controller is just not going to work
		}

		return $this;
	}

	/**
	 * Does the route match the request?
	 *
	 * @param Request $request
	 * @return bool
	 */
	public function isMatching(Request $request): bool
	{
		if ($this->isThisMethod($request) && $this->isThisUri($request)) {
			return true;
		}

		if (! _String::contains($this->uriRegex, '+', '*')) {
			return false;
		}

		preg_match_all("#$this->uriRegex?$#", $request->getNormalisedUri(), $matches);

		if (empty($matches = array_filter(
			$matches[1], fn ($item) => empty($item) || $item[0] !== $request->getNormalisedUri()
		))) return false;

		$this->parameters = array_combine(
			$this->parameters,
			array_values(array_map(fn ($match) => $match, $matches)) +
			       array_fill(0, count($this->parameters), null)
		);

		return true;
	}

	/**
	 * Get the action of this particular route.
	 *
	 * @return array
	 */
	public function getAction(): array
	{
		return $this->action;
	}

	/**
	 * Add a parameter to the router.
	 *
	 * @param string $parameter
	 * @return void
	 */
	public function addParameter(string $parameter): void
	{
		$this->parameters[] = $parameter;
	}

	/**
	 * Get the parameters that are associated to this particular route.
	 *
	 * @return array
	 */
	public function getParameters(): array
	{
		return $this->parameters;
	}

	/**
	 * Does the request match the route method.
	 *
	 * @param Request $request
	 * @return bool
	 */
	public function isThisMethod(Request $request): bool
	{
		return $this->getMethod() === $request->getMethod();
	}

	/**
	 * Does the request match the route uri.
	 *
	 * @param Request $request
	 * @return bool
	 */
	public function isThisUri(Request $request): bool
	{
		return $this->getUri() === $request->getUri();
	}

	/**
	 * Dispatch the route that has been matched against the particular request; build out the process that's needed
	 * in order for this particular route to be executed.
	 *
	 * @param Application $application
	 * @param Request $request
	 * @return Response
	 */
	public function dispatch(Application $application, Request $request): Response
	{
		[$routeController, $routeAction] = $this->getAction();

		// attach the request to the parameters of the route.
		$this->parameters['request'] = $request;

		// if we haven't defined a controller for this particular route then it means that
		// we're dealing with a closure meaning that we can just simply return here without
		// needing to do anything else.
		if (! $routeController) {
			return $routeAction(...$this->buildRouteParameters($routeAction));
		}

		$routeController = $application->make($routeController);

		return $routeController->{$routeAction}(
			...$this->buildRouteParameters($routeController, $routeAction)
		);
	}

	/**
	 * Build the route parameters that's going to be needed to be used by the controller, pass in the necessary
	 * parameters for the controller to run the route.
	 *
	 * @param object $object
	 * @param string|null $action
	 * @return array
	 */
	private function buildRouteParameters(object $object, string $action = null): array
	{
		$neededParameters = _Reflect::getClassMethodParameterNames($object, $action);

		return array_filter($this->getParameters(), function ($key) use ($neededParameters) {
			return array_key_exists($key, $neededParameters);
		}, ARRAY_FILTER_USE_KEY);
	}
}