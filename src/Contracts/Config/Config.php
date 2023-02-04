<?php

namespace Vyui\Contracts\Config;

interface Config
{
    /**
     * Get a set config from the application's configuration bank.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;
}