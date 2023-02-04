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
        array $server = []
    ) {
        $this->initialise($query, $request, $attributes, $cookies, $files, $server);
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
        return new static($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
    }

    /**
     * @param array $query  the GET parameters of the request
     * @param array $request the POST parameters of the request
     * @param array $attributes the request attributes (parameters parsed from PATH_INFO, ...)
     * @param array $cookies the COOKIE parameters
     * @param array $files  the FILES parameters
     * @param array $server the SERVER parameters
     * @return void
     */
    public function initialise(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = []
    ): void {
        $this->query = new GetParameters($query);
        $this->request = new PostParameters($request);
        $this->attributes = new AttributeParameters($attributes);
        $this->cookies = new CookieParameters($cookies);
        $this->files = new FileParameters($files);
        $this->server = new ServerParameters($server);
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
     * Get an input from the request (_POST)
     *
     * @param string $key
     * @param $default
     * @return mixed
     */
    public function input(string $key, $default = null): mixed
    {
        return $this->request->get($key, $default);
    }

    /**
     * Get the method that the request is using.
     *
     * @return string|null
     */
    public function getMethod(): ?string
    {
        return $this->server('request_method');
    }

    /**
     * get the url of the application
     *
     * @return string
     */
    public function getUri(): string
    {
        return $this->server('php_self') ??
               $this->server('request_uri');
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
        return $this->server($header);
    }

    /**
     * Method for getting particular variables out of the server variable.
     *
     * @param string $key
     * @return mixed
     */
    private function server(string $key): mixed
    {
        return $this->server->get(mb_strtoupper($key));
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
}