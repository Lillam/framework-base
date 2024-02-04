<?php

namespace Vyui\Foundation\Http;

use Vyui\Foundation\Http\Request\GetParameters;
use Vyui\Foundation\Http\Request\FileParameters;
use Vyui\Foundation\Http\Request\PostParameters;
use Vyui\Foundation\Http\Request\CookieParameters;
use Vyui\Foundation\Http\Request\ServerParameters;
use Vyui\Foundation\Http\Request\AttributeParameters;

class Request
{
    /**
     * The GET parameters of the request.
     *
     * @var GetParameters
     */
    protected GetParameters $query;

    /**
     * The POST parameters of the request.
     *
     * @var PostParameters
     */
    protected PostParameters $request;

    /**
     * The Request attributes (parameters of which are passed from the PATH_INFO...)
     *
     * @var AttributeParameters
     */
    protected AttributeParameters $attributes;

    /**
     * The COOKIE parameters for the request.
     *
     * @var CookieParameters
     */
    protected CookieParameters $cookies;

    /**
     * The FILES parameters of the request.
     *
     * @var FileParameters
     */
    protected FileParameters $files;

    /**
     * The SERVER parameters of the request.
     *
     * @var ServerParameters
     */
    protected ServerParameters $server;

    /**
    * The request body
    *
    * @var string | null
    */
    protected ?string $content = null;

    protected bool $constructedFromContainer;

    /**
     * @param array $query The GET parameters of the request.
     * @param array $request The POST parameters of the request.
     * @param array $attributes The request attributes (parameters of which are passed from the PATH_INFO, ...)
     * @param array $cookies The COOKIE parameters
     * @param array $files The FILES parameters
     * @param array $server the SERVER parameters
     */
    public function __construct(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        bool $constructedFromContainer = true
    ) {
        $this->query = new GetParameters($query);
        $this->request = new PostParameters($request);
        $this->attributes = new AttributeParameters($attributes);
        $this->cookies = new CookieParameters($cookies);
        $this->files = new FileParameters($files);
        $this->server = new ServerParameters($server);

        $this->constructedFromContainer = $constructedFromContainer;

        $this->content = $this->getContent();
    }

    /**
     * @return static
     */
    public static function capture(): static
    {
        return self::createFromGlobals();
    }

    /**
     * Creates and returns a new request with values from PHP's super global variables.
     *
     * @return static
     */
    protected static function createFromGlobals(): static
    {
        return new static($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER, false);
    }

    /**
     * Get an input variable from the request (_GET) super global.
     *
     * @param string $key
     * @param $default
     * @return mixed
     */
    public function get(string $key, $default = null): mixed
    {
        return $this->query->get($key, $default);
    }

    /**
    * get variables from the request (_GET) super global
    *
    * @param array $keys
    * @return array
    */
    public function all(...$keys): array
    {
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $this->input($key);
        }

        return $result;
    }

    /**
     * Get an input from the request (_POST)
     *
     * @param string $key
     * @param $default
     * @return mixed
     */
    public function input(string $key, $default = null): mixed
    {
        return $this->{$this->getMethodParameterHandler()}->get($key, $default);
    }

    /**
     * Get the method that the request is using. this method is going to return one of the following request methods:
     * PUT, POST, PATCH, DELETE, GET as a string.
     *
     * @return string|null
     */
    public function getMethod(): ?string
    {
        return $this->getServer()->get('REQUEST_METHOD');
    }

    /**
    * A utility method for the request to figure out where the input wants to be coming from.
    *
    * @return string query|request (GET parameters or POST parameters)
    */
    private function getMethodParameterHandler(): string
    {
        return $this->isMethod('HEAD') || $this->ismethod('GET')
            ? 'query'
            : 'request';
    }

    /**
    * Check to see whether this method is the method we're expected or not.ß
    *
    * @param string $method -> the method in which we are checking against
    * @return bool
    */
    public function isMethod(string $method): bool
    {
        return $this->getMethod() === $method;
    }

    /**
     * get the url of the application, we are going to acquire this from the PHP_SELF otherwise; if this ends up being
     * index.php we are instead going to acquire it from the REQUEST_URI instead.
     *
     * @return string
     */
    public function getUri(): string
    {
        if (! str_contains($uri = $this->getServer()->get('PHP_SELF'), 'index.php')) {
            return $uri;
        }

        return $this->getServer()->get('REQUEST_URI');
    }

    /**
     * check a given uri against the one that's been hit from the server.
     *
     * @param string $uri
     * @return bool
     */
    public function isUri(string $uri): bool
    {
        return $this->getUri() === $uri;
    }

    /**
     * Get a normalised version of the url string of this particular request.
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
     * Method for acquiring a header from the request
     *
     * @param string $header
     * @return mixed
     */
    public function getHeader(string $header): mixed
    {
        return $this->getServer()->get($header);
    }

    /**
     * @return GetParameters
     */
    public function getQuery(): GetParameters
    {
        return $this->query;
    }

    /**
     * @return PostParameters
     */
    public function getRequest(): PostParameters
    {
        return $this->request;
    }

    /**
     * @return CookieParameters
     */
    public function getCookies(): CookieParameters
    {
        return $this->cookies;
    }

    /**
     * @return FileParameters
     */
    public function getFiles(): FileParameters
    {
        return $this->files;
    }

    /**
     * @return ServerParameters
     */
    public function getServer(): ServerParameters
    {
        return $this->server;
    }

    /**
     * @return AttributeParameters
     */
    public function getAttributes(): AttributeParameters
    {
        return $this->attributes;
    }

    /**
     * Acquire all the information about the request; getting all the parameters and merging them all into a singular
     * array that we can return to the user.
     *
     * @return array
     */
    public function getAllParameters(): array
    {
        return [
            ...$this->getAttributes()->all(),
            ...$this->getQuery()->all(),
            ...$this->getRequest()->all(),
            ...$this->getFiles()->all(),
            ...$this->getServer()->all(),
            ...$this->getCookies()->all(),
        ];
    }

    /**
    * At the point a request is made, Get the content of the request body.
    *
    * @return string
    */
    public function getContent(): string
    {
        if (! $this->content) {
            $this->content = file_get_contents('php://input');
        }

        if (! $this->isMethod('GET') && ! $this->isMethod('HEAD')) {
            // dd($this->constructedFromContainer, debug_backtrace());
        //     // dd(
        //     //     'here',
        //     //     $this->isMethod('GET'),
        //     //     $this->isMethod('HEAD'),
        //     //     $this->getMethod(),
        //     //     $this->getServer()->all(),
        //     //     $this
        //     // );
        //     //
        //     //
        //     dd($this->constructedFromContainer);

            $this->request->merge(json_decode($this->content, true));
        }

        return $this->content;
    }
}
