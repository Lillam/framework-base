<?php

namespace Vyui\Contracts\Http;

interface Response
{
    /**
     * Send both the HTTP Headers and Content and then wrap the request up.
     *
     * @return $this
     */
    public function send(): self;

    /**
     * Send the content (basically echoing the content to the frontend client)
     *
     * @return $this
     */
    public function sendContent(): self;

    /**
     * Send the headers of the content, alerting the requester know of the status code.
     *
     * @return $this
     */
    public function sendHeaders(): self;
}
