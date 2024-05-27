<?php

namespace Vyui\Services\Routing;

use Closure;
use Vyui\Foundation\Application;
use Vyui\Foundation\Http\Request;
use Vyui\Services\Database\Model;
use Vyui\Support\Helpers\_String;
use Vyui\Foundation\Http\Response;
use Vyui\Support\Helpers\_Reflect;
use Vyui\Foundation\Http\Controller;

class Route
{
    /**
    * @param Application $application -> belated creation, only exists after being passed through a kernel that
    *                                    injects the application to the route in question; otherwise each route
    *                                    in the application's lifecycle will be created with an application.
    */
    protected Application $application;

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
     * [controller, action] (tuple)
     * @var array
     */
    protected array $action;

    /**
     * @var array
     */
    protected array $parameters = [];

    /**
     * @var array
     */
    protected array $optionalParameters = [];

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

    public function getFullUri(): string
    {
        return env('APP_URL') . $this->uri;
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
            'object' => [$action, null],
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
        // if the method of the request is matching and if the uri is matching, then we are able to just immediately
        // return true; as this would be a direct match.
        if ($this->matchesMethod($request) && $this->matchesUri($request)) {
            return true;
        }

        if (! _String::contains($this->getUriRegex(), '+', '*')) {
            return false;
        }

        preg_match_all("#{$this->getUriRegex()}?$#", $request->getNormalisedUri(), $matches);

        if (empty($matches = array_filter(
            $matches[1], fn ($item) => empty($item) || $item[0] !== $request->getNormalisedUri()
        ))) {
            return false;
        }

        $this->parameters = array_combine(
            $this->parameters,
            array_values(array_map(fn ($match) => $match, $matches)) +
            array_fill(0, (count($this->parameters)), null)
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
     * Add a parameter to the route.
     *
     * @param string $parameter
     * @return void
     */
    public function addParameter(string $parameter): void
    {
        $this->parameters[] = rtrim($parameter, '?');
        if (mb_strpos($parameter, '?') !== false) {
            $this->addOptionalParameter(rtrim($parameter, '?'));
        }
    }

    /**
     * Add an optional parameter to the route.
     *
     * @param string $parameter
     * @return void
     */
    public function addOptionalParameter(string $parameter): void
    {
        $this->optionalParameters[] = $parameter;
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
    public function matchesMethod(Request $request): bool
    {
        return $this->getMethod() === $request->getMethod();
    }

    /**
     * Does the request match the route uri.
     *
     * @param Request $request
     * @return bool
     */
    public function matchesUri(Request $request): bool
    {
        return $this->getUri() === $request->getUri();
    }

    /**
     * Dispatch the route that has been matched against the particular request; build out the process that's needed
     * in order for this particular route to be executed.
     * @todo -> remove this particular piece of code and place it into the container which will be under the ->call() method
     *          instead; this is a lot of responsibility right now for the route to be doing, and is almost out of it's scope.
     *
     * @param Application $application
     * @param Request $request
     * @return Response
     */
    public function dispatch(Application $application, Request $request): Response
    {
        $this->application = $application;

        [$controller, $action] = $this->getAction();

        // if we haven't defined a controller for this particular route then it means that
        // we're dealing with a closure meaning that we can just simply return here without
        // needing to do anything else.
        if (! $action) {
            return $controller(...$this->buildParameters($controller));
        }

        // Have the application create the specified controller along with all it's dependencies.
        $controller = $application->make((string) $controller);

        /** @var Controller $controller */
        return $controller->throughMiddleware($request)
                          ->call($action, $this->buildParameters($controller, $action));
    }

    /**
     * Build the route parameters that's going to be needed to be used by the controller, pass in the necessary
     * parameters for the controller to run the route.
     *
     * @param object $object
     * @param string|null $action
     * @return array
     */
    private function buildParameters(object $object, string $action = null): array
    {
        $neededParameters = _Reflect::getClassMethodParameterInfo($object, $action);

        // iterate over the parameters of the request which will be the ones that we've decided that are necessary
        // to the request; and if this is the case; then we're simply going to filter the ones that aren't really
        // necessary to the request, or necessary in terms of what's been asked for within the controller that's going
        // to be spooled up.
        $parameters = array_filter(
            $this->getParameters(),
            function ($key) use ($neededParameters) {
                return array_key_exists($key, $neededParameters);
            },
            ARRAY_FILTER_USE_KEY
        );

        // Iterate over the parameters as see if there had been anything stored in the parameters for them that the
        // controller method has; this will be taking care of some particular bindings that is needed; such as being
        // able to implement a particular type of model into the method.
        foreach ($parameters as $parameterKey => $parameter) {
            if (array_key_exists($parameterKey, $neededParameters)) {
                $type = $neededParameters[$parameterKey]['type'];

                // right here we are going to have unassigned this from the needed parameters so that if there is still
                // anything left within the needed parameters then we are still needing to be makign something and
                // building it out for later use...
                unset($neededParameters[$parameterKey]);

                // if the type has been passed as an item of which is null; then we can immediately do nothing and just
                // skip passed this particular item as nothing really needs to happen here.
                if (! $type) {
                    continue;
                }

                // if the type has been specified to be a string; then we're good to just iterate on past this
                // particular parameter; and simply move on.
                if ($type === "string") {
                    continue;
                }

                // if the type has been specified to be an integer; then we are going to attempt to cast the particular
                // value as an integer.
                if ($type === "int") {
                    $parameters[$parameterKey] = (int) $parameter;
                    continue;
                }

                // if the type of the object is of the type Model; then we are safe to attempt to perform some operation
                // that finds the particular model, and dumps the value into the controller; here we are capable of
                // returning a 404 if the particular model cannot be found.
                if ($type === Model::class) {
                    $model = new $type;
                    $parameters[$parameterKey] = $model::query()->where($model->getPrimaryKey(), '=', $parameter)
                                                                ->first();
                }
            }
        }

        // if we still have any needed parameters left at this point, then we are going to be needing to build them out
        // and look to start auto wiring right there.
        foreach ($neededParameters as $neededKey => $neededParameter) {
            if (in_array($neededParameter['type'], ['int', 'string', 'boolean'])) {
                continue;
            }

            $neededParameters[$neededKey] = $this->application->make($neededParameter['type']);
        }

        return array_merge($parameters, $neededParameters);
    }
}
