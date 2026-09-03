<?php

namespace Vyui\Foundation\Http;

class Response implements ResponseContract
{
    /**
     * @param string $content -> The content that is going to be rendered to the client.
     * @param int $status     -> The status of the request that had been made.
     * @param array $headers  -> The headers of the request hat had been made.
     */
    public function __construct(
        protected string $content,
        protected int $status = 200,
        protected array $headers = []
    ) {
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
        if (!headers_sent()) {
            header('Access-Control-Allow-Origin: *');
            array_map(fn ($header) => header($header), $this->headers);
        }

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
