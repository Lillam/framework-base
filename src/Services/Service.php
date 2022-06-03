<?php

namespace Vyui\Services;

use Vyui\Foundation\Application;

abstract class Service
{
    /**
     * The instance of the Application.
     *
     * @var Application
     */
    protected Application $application;

    /**
     * Has this provider been bootstrapped.
     *
     * @var bool
     */
    protected bool $bootstrapped = false;

    /**
     * Constructor of the Service. This particular Service (abstraction) should never be instantiated. and always
     * containing the Application instance.
     *
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Register the provider
     *
     * @return void
     */
    abstract public function register(): void;

    /**
     * Bootstrap the provider.
     *
     * @return void
     */
    abstract public function bootstrap(): void;

    /**
     * Utility method for the providers; any class extending this one is going to want to have a method that returns
     * its own class name as a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return static::class;
    }
}