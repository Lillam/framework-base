<?php

namespace Vyui\Contracts;

use Vyui\Services\Service;

interface Application
{
    /**
     * Set the base path for the application.
     *
     * @param string $basePath
     * @return $this
     */
    public function setBasePath(string $basePath): self;

    /**
     * Get the base path for the application.
     *
     * @param string|null $path
     * @return string
     */
    public function getBasePath(?string $path = null): string;

    /**
     * Register a provider into the application.
     *
     * @param Service $service
     * @param string|null $registerAs
     * @return void
     */
    public function register(Service $service, string $registerAs = null): void;

    /**
     * Give control to the container in order to make the abstraction.
     *
     * @param string $abstract
     * @param array $parameters
     * @return mixed
     */
    public function make(string $abstract, array $parameters = []): mixed;
}