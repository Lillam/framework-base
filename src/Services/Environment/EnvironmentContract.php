<?php

namespace Vyui\Services\Environment;

interface EnvironmentContract
{
    /**
     * Set the name of the environment file which will be loaded into memory.
     *
     * @param string $file
     * @return $this
     */
    public function setFile(string $file): self;

    /**
     * Get the environment file name.
     *
     * @return string
     */
    public function getFile(): string;

    /**
     * Setting the location of the environment file.
     *
     * @param string $path
     * @return $this
     */
    public function setPath(string $path): self;

    /**
     * Get the environment file location.
     *
     * @param string|null $file
     * @return string
     */
    public function getPath(?string $file = null): string;

    /**
     * Set a targeted variable within the environment.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, mixed $value): void;

    /**
     * Get a targeted variable within the environment.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Get all variables that are stored within the environment.
     *
     * @return array<string, mixed>
     */
    public function all(): array;

    /**
     * Load all the environment variables and instantiate them into the environment in memory for ease of access
     * to the variables that reside within.
     *
     * @return self
     */
    public function loadEnvironmentVariables(): self;

    /**
     * Synonymous of loadEnvironmentVariables however coined to be utilised in a moment where one needs to reload the
     * environment variables of the application. This method should always be calling the loadEnvironmentVariables()
     * method.
     *
     * @return self
     */
    public function reloadEnvironmentVariables(): self;

    /**
     * Cache all environment variables to take heat away from the application having to set it all.
     *
     * @return void
     */
    public function cache(): void;
}
