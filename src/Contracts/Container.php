<?php

namespace Vyui\Contracts;

use Closure;
use Vyui\Foundation\Container\Container as ApplicationContainer;

interface Container
{
    /**
     * Set the global access to the available container.
     *
     * @param ApplicationContainer|null $container
     * @return ApplicationContainer|static|null
     */
    public static function setInstance(ApplicationContainer $container = null): ApplicationContainer|static|null;

    /**
     * Get the global access to the available container.
     *
     * @return ApplicationContainer|static|null
     */
    public static function getInstance(): ApplicationContainer|static|null;

    /**
     * Set up an abstract instance into the container that we can re-use later as a shared resources. using this will
     * override any instance that would have previously been bound.
     *
     * @param string $abstract
     * @param mixed $instance
     * @return mixed
     */
    public function instance(string $abstract, mixed $instance): mixed;

    /**
     * Confirm if a given concrete against an abstraction is buildable.
     *
     * @param string $abstract
     * @param string|Closure|null $concrete
     * @return bool
     */
    public function isBuildable(string $abstract, string|Closure|null $concrete): bool;

    /**
     * Confirm if a given concrete against an abstraction is not buildable.
     *
     * @param string $abstract
     * @param string|Closure $concrete
     * @return bool
     */
    public function isNotBuildable(string $abstract, string|Closure $concrete): bool;

    /**
     * Get all the abstractions that have been resolved in the container.
     *
     * @param bool $withCount - get the resolved abstractions with a number of times abstractions have been resolved.
     * @return array
     */
    public function resolved(bool $withCount): array;
}
