<?php

namespace Vyui\Foundation\Http;

use Exception;
use BadMethodCallException;
use Vyui\Foundation\Http\Middleware\Middleware;

abstract class Controller
{
    /**
     * The default invocation method that would be called when the controller is simply invoked.
     * (new controller)() for example would simply call on the setup controller (new controller)->default() requiring a
     * public function default() on the controller in question.
     *
     * @var string
     */
    protected static string $defaultInvocation = "index";

    /**
     * @var Middleware[]
     */
    protected array $middleware = [];

    /**
     * @param Middleware[]|string[] $middleware
     * @return void
     */
    public function middleware(array $middleware)
    {
        foreach ($middleware as $m) {
            $this->middleware[] = is_object($m) ? $m : new $m();
        }
    }

    /**
     * Get the middleware that's set against a particular controller.
     *
     * @return Middleware[]
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    /**
     * Send the request through the middleware that is currently attached to this particular
     * controller
     *
     * @param Request $request
     * @return static
     */
    public function throughMiddleware(Request $request): static
    {
        foreach ($this->middleware as $middleware) {
            (new $middleware())->handle($request);
        }

        return $this;
    }

    /**
     * Return a response from the controller as all controllers will want to return some kind of
     * response to the invoker.
     *
     * @param mixed $data (string, number, array, object | class...)
     * @param int $code
     * @return Response
     * @note -> object might not be the best to handle (json_encode) and can possibly
     *          create an interface (JsonSerializable) or just (Serializable)
     */
    public function respond(mixed $data, int $code = 200): Response
    {
        $response = match (gettype($data)) {
            "array", "object" => json_encode($data),
            "int" => (string) $data,
            default => $data,
        };

        return new Response($response, $code);
    }

    /**
     *
     * @param string $method
     * @param array $parameters
     * @return Response
     */
    public function call(string $method, array $parameters): Response
    {
        return $this->{$method}(...$parameters);
    }

    /**
     * Handles calls to missing methods on the controller in question that's being targetted via the current route.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        throw new BadMethodCallException("Method " . static::class . "::" . $method . " does not exist.");
    }

    /**
     * Set a default action when invoking a controller... this will call a default method on a controller class.
     *
     * @return mixed
     * @throws Exception
     */
    public function __invoke(): Response
    {
        if (method_exists($class = static::class, self::$defaultInvocation)) {
            return $this->{static::$defaultInvocation}();
        }

        throw new Exception("Default invocation method is not set on [$class]");
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return static::class;
    }
}
