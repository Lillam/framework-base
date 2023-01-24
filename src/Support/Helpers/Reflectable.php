<?php

namespace Vyui\Support\Helpers;

use ReflectionClass;
use ReflectionMethod;
use ReflectionException;

class Reflectable
{
    protected ReflectionClass $reflection;

    /**
     * A variable in which would be used for filtering out the methods for the reflection methods.
     *
     * @var string|null
     */
    protected ?string $filterMethodsByName = null;

    /**
     * Create an instance of a reflection so that we can objectively work with the type.
     *
     * @param string|object $class
     * @throws ReflectionException
     */
    public function __construct(string|object $class)
    {
        $this->reflection = new ReflectionClass($class);
    }

    /**
     * Set the parameter where if we collect methods then we can specify a name that is filtered by; which will return
     * methods where the names contain the passed parameter.
     *
     * @param string $contains
     * @return $this
     */
    public function filterMethodsWhereContains(string $contains): static
    {
        $this->filterMethodsByName = $contains;

        return $this;
    }

    /**
     * Get all the methods that are against a class... and return them
     *
     * @return ReflectionMethod[]
     */
    public function getMethods(): array
    {
        // if this object had been specified with a filter methods by name, then we're going to be setting up a filter
        // for the methods and return them where the methods contain the string that's in the name of.
        if ($this->filterMethodsByName) {
            return array_filter($this->reflection->getMethods(), fn (ReflectionMethod $method) =>
                str_contains($method->getName(), $this->filterMethodsByName)
            );
        }

        return $this->reflection->getMethods();
    }
}