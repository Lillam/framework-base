<?php

namespace Vyui\Foundation\Http;

class Response implements ResponseContract
{
    /**
     * The headers that will be handed back to the client.
     * 
     * @param HeaderBag $headers
     */
    protected HeaderBag $headers;

    /**
     * Cookies held as objects until send time, so that anything further
     * along the middleware stack is able to add to or rewrite them.
     */
    protected array $cookies = [];

    /**
     * @param string $content -> The content that is going to be rendered to the client.
     * @param int $status     -> The status of the request that had been made.
     * @param array $headers  -> The headers of the request hat had been made.
     */
    public function __construct(
        protected string $content,
        protected int $status = 200,
        array $headers = []
    ) {
        $this->headers = new HeaderBag($headers);
    }

    /**
     * Attach a header to the response to the requesting user.
     * 
     * @param string $name
     * @param string $value
     * 
     * @return self
     */
    public function withHeader(string $name, string $value): self
    {
        $this->headers->set($name, $value);

        return $this;
    }

    /**
     * Attach a cookie to the response to be shipped back to 
     * the requesting user.
     * 
     * @param Cookie $cookie
     */
    public function withCookie(Cookie $cookie): self
    {
        $this->cookies[$cookie->getName()] = $cookie;

        return $this;
    }

    /**
     * Set the status of the response.
     * 
     * @param int $status
     * @return self 
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;
        
        return $this;
    }

    /**
     * Send both the HTTP Headers and Content and then wrap the request up.
     *
     * @return self
     */
    public function send(): self
    {
        $this->sendHeaders();
        $this->sendContent();

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }

        return $this;
    }

    /**
     * Send the content (basically echoing the content to the frontend client)
     *
     * @return self
     */
    public function sendContent(): self
    {
        echo $this->content;

        return $this;
    }

    /**
     * Send the headers of the request had been made.
     *
     * @return self
     */
    public function sendHeaders(): self
    {
        if (\headers_sent()) {
            return $this;
        }

        foreach ($this->headers->all() as $header => $value) {
            header("$header: $value");
        }

        foreach ($this->cookies as $cookie) {
            \header("Set-Cookie: $cookie", false);
        }

        \http_response_code($this->status);

        return $this;
    }

    /**
     * Return the response as a json response.
     *
     * @param mixed $data
     * @return self
     * @note -> figure a means in which allows to set the default key for responding from the
     *          api oriented style controllers; 'data' might not always be a desired key.
     *          potential response format as (data.data.any)
     */
    public function json(mixed $data): self
    {                
        $this->content = json_encode(
            !\is_array($data) ? ['data' => $data]
                             : $data,
            JSON_THROW_ON_ERROR
        );

        return $this;
    }

    /**
     * Return the response as a pure string content, if the object is cast to a string then we're just simply
     * going to return the content.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->content;
    }
}
