<?php

namespace Vyui\Services\Facades;

use RuntimeException;
use ReflectionException;
use Vyui\Contracts\Container;
use Vyui\Foundation\Application;
use Vyui\Exceptions\Container\BindingResolutionException;

abstract class Facade
{
    protected static Container $application;

    /**
     * Set the application's instance to the Facade wrapper.
     *
     * @param Application $application
     * @return void
     */
    final public static function setFacadeApplication(Application $application): void
    {
        static::$application = $application;
    }

    /**
     * Get the name of the registered abstraction.
     *
     * @return string
     * @throws RuntimeException
     */
    protected static function getFacadeAccessor(): string
    {
        throw new RuntimeException('Facade: [' . static::class . '] does not implement getFacadeAccessor.');
    }

    /**
     * Handle all static calls dynamically to a given object.
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public static function __callStatic(string $method, array $arguments = []): mixed
    {
        $instance = static::$application->make(static::getFacadeAccessor());

        return $instance->$method(...$arguments);
    }
}