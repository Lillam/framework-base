<?php

namespace Vyui\Foundation\Http;

interface ResponseContract
{
    /**
     * Send both the HTTP Headers and Content and then wrap the request up.
     *
     * @return self
     */
    public function send(): self;

    /**
     * Send the content (basically echoing the content to the frontend client)
     *
     * @return self
     */
    public function sendContent(): self;

    /**
     * Send the headers of the content, alerting the requester know of the status code.
     *
     * @return self
     */
    public function sendHeaders(): self;
}
