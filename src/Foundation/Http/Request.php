<?php

namespace Vyui\Foundation\Http;

class Request
{
    /**
     * The GET parameters of the request.
     *
     * @var array
     */
    protected array $request;

    /**
     * The POST parameters of the request.
     *
     * @var array
     */
    protected array $query;

    /**
     * The Request attributes (parameters of which are passed from the PATH_INFO...)
     *
     * @var array
     */
    protected array $attributes;

    /**
     * The COOKIE parameters for the request.
     *
     * @var array
     */
    protected array $cookies;

    /**
     * The FILES parameters of the request.
     *
     * @var array
     */
    protected array $files;

    /**
     * The SERVER parameters of the request.
     *
     * @var array
     */
    protected array $server;

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
        $this->query = $query;
        $this->request = $request;
        $this->attributes = $attributes;
        $this->cookies = $cookies;
        $this->files = $files;
        $this->server = $server;
    }

    /**
     * Get an input variable from the request (_GET, _POST) super globals.
     *
     * @param string $key
     * @param $default
     * @return mixed
     */
    public function get(string $key, $default = null): mixed
    {
        return $this->query[$key] ?? $this->request[$key] ?? $default;
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
        return $this->server('request_uri');
    }

	/**
	 * check a given uri against the one that's been hit from the server.
	 *
	 * @param string $uri
	 * @return bool
	 */
	public function isUri(string $uri): bool
	{
		return $this->server('request_uri') === $uri;
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
     * Method for getting particular variables out of the server variable.
     *
     * @param string $key
     * @return mixed
     */
    private function server(string $key): mixed
    {
        return $this->server[strtoupper($key)] ?? null;
    }
}