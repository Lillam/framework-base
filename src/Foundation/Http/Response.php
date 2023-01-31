<?php

namespace Vyui\Foundation\Http;

use Vyui\Contracts\Http\Response as ResponseContract;

class Response implements ResponseContract
{
    /**
     * The content that is going to be rendered to the client.
     *
     * @var string
     */
    protected string $content;

    /**
     * The status of the request that had been made.
     *
     * @var int
     */
    protected int $status;

    /**
     * The headers of the request hat had been made.
     *
     * @var array
     */
    protected array $headers;

    /**
     * @param string $content
     * @param int $status
     * @param array $headers
     */
    public function __construct(string $content, int $status = 200, array $headers = [])
    {
		$this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
    }

    /**
     * Send both the HTTP Headers and Content and then wrap the request up.
     *
     * @return $this
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
     * @return $this
     */
    public function sendHeaders(): self
    {
		if (! headers_sent()) {
            header('Access-Control-Allow-Origin: *');
            array_map(fn ($header) => header($header), $this->headers);
		}

		return $this;
    }

    /**
     * Return the response as a json response.
     *
     * @param array $data
     * @return $this
     */
    public function json(array $data): self
    {
        $this->content = json_encode($data);

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