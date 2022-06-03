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
	 * @param string $content
	 */
    public function __construct(string $content)
    {
		$this->content = $content;
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

    public function sendHeaders(): self
    {
		if (headers_sent()) {
			return $this;
		}

		header('Access-Control-Allow-Origin: *');

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