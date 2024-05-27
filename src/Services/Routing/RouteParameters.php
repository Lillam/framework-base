<?php

namespace Vyui\Services\Routing;

class RouteParameters
{
    protected array $parameters = [];

    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }
}
