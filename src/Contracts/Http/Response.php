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
     * @return self
     */
    public function sendContent(): self;

    public function sendHeaders(): self;
}