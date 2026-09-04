<?php

namespace Vyui\Foundation\Http;

class Request
{
    /**
     * The GET parameters of the request.
     *
     * @var ParameterBag
     */
    protected ParameterBag $query;

    /**
     * The POST parameters of the request.
     *
     * @var ParameterBag
     */
    protected ParameterBag $request;

    /**
     * The Request attributes (parameters of which are passed from the PATH_INFO...)
     *
     * @var ParameterBag
     */
    protected ParameterBag $attributes;

    /**
     * The COOKIE parameters for the request.
     *
     * @var ParameterBag
     */
    protected ParameterBag $cookies;

    /**
     * The FILES parameters of the request.
     *
     * @var ParameterBag
     */
    protected ParameterBag $files;

    /**
     * The SERVER parameters of the request.
     *
     * @var ParameterBag
     */
    protected ParameterBag $server;

    /**
     * The Headers that are extracted from the server.
     * 
     * @var HeaderBag
     */
    protected HeaderBag $headers;

    /**
    * The request body
    *
    * @var string | null
    */
    protected ?string $content = null;

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
        $this->query      = new ParameterBag($query);
        $this->request    = new ParameterBag($request);
        $this->attributes = new ParameterBag($attributes);
        $this->cookies    = new ParameterBag($cookies);
        $this->files      = new ParameterBag($files);
        $this->server     = new ParameterBag($server);
        $this->headers    = new HeaderBag($this->getHeadersFromServer());
        $this->content    = $this->getContent();
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
            $result[$key] = $this->input((string) $key);
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
     * @return string
     */
    public function method(): string
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
        return $this->method() === $method;
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
        return (string) preg_replace(
            '/[\/]{2,}/',
            '',
            '/' . trim($this->getUri(), '/') . '/'
        );
    }

    /**
     * Method for acquiring a header from the request, if this can't be 
     * found within the built headers parameter bag, then look again but 
     * this time look within the server parameter bag.
     *
     * @param string $header
     * @return mixed
     */
    public function header(string $header, mixed $default = null): mixed
    {
        return $this->headers->get($header, $default);
    }

    /**
     * Method for acquiring all the headers from the request.
     */
    public function headers(): array 
    {
        return $this->headers->all();
    }

    /**
     * Check to see if the request has the requested header
     */
    public function hasHeader(string $header): bool
    {
        return $this->headers->has($header);
    }

    /**
    * get the authorization from the header...
    *
    * @return string | null
    */
    public function getAuthorization(?string $type = 'Bearer'): ?string
    {
        return (string) str_replace("$type ", '', $this->getServer()->get('HTTP_AUTHORIZATION'));
    }

    /**
    * Alias for getAuthorization
    *
    * @return string | null
    */
    public function getAuth(?string $type): ?string
    {
        return $this->getAuthorization($type);
    }

    /**
     * @return ParameterBag
     */
    public function getQuery(): ParameterBag
    {
        return $this->query;
    }

    /**
     * @return ParameterBag
     */
    public function getRequest(): ParameterBag
    {
        return $this->request;
    }

    /**
     * @return ParameterBag
     */
    public function getCookies(): ParameterBag
    {
        return $this->cookies;
    }

    /**
     * @return ParameterBag
     */
    public function getFiles(): ParameterBag
    {
        return $this->files;
    }

    /**
     * @return ParameterBag
     */
    public function getServer(): ParameterBag
    {
        return $this->server;
    }

    /**
     * @return ParameterBag
     */
    public function getAttributes(): ParameterBag
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

    private function getHeadersFromServer(): array
    {
        $headers = [];

        foreach ($this->server->all() as $header => $value) {
            if (\str_starts_with($header, "HTTP_")) {
                $headers[\substr($header, 5)] = $value;
            }
        }

        foreach (['CONTENT_TYPE', "CONTENT_LENGTH", "CONTENT_MD5"] as $header) {
            if (($value = $this->server->get($header)) !== null) {
                $headers[$header] = $value;
            }
        }
        
        // Apache strips the Authporisation header out unless it has been 
        // explicitly passed through, in which case it tends to surface 
        // under the redirect key.
        if (! isset($headers['AUTHORIZATION']) && ($auth = $this->server->get('REDIRECT_HTTP_AUTHORIZATION'))) {
            $headers['AUTHORIZATION'] = $auth;
        }

        return $headers;
    }

    /**
    * At the point a request is made, Get the content of the request body.
    *
    * @return string
    */
    public function getContent(): string
    {
        if (! $this->content) {
            $this->content = (string) \file_get_contents('php://input');
        }

        if (! $this->isMethod('GET') && ! $this->isMethod('HEAD')) {
            $this->request->merge(\json_decode($this->content, true));
        }

        return $this->content;
    }
}
