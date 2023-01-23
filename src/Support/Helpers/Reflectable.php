<?php

namespace Vyui\Support\Helpers;

use Closure;
use ReflectionClass;
use ReflectionMethod;

class Reflectable
{
    protected ReflectionClass $reflection;

    public function __construct(string|object $class)
    {
        $this->reflection = new ReflectionClass($class);
    }

    /**
     * Get all the methods that are against a class... and return them
     *
     * @param Closure|null $filter
     * @return ReflectionMethod[]
     */
    public function getMethods(?Closure $filter = null): array
    {
        return $filter ? array_filter($this->reflection->getMethods(), $filter)
                       : $this->reflection->getMethods();
    }
}