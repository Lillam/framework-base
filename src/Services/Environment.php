<?php

namespace Vyui\Services;

use Vyui\Services\Service;

class Environment extends Service
{
    /**
     * The location where you can find the environment file (.env).
     *
     * @var string
     */
    protected string $path {
        get => $this->application->getBasePath("/");
        set => rtrim($value, '/');
    }

    /**
     * The name of the file that will be loaded into memory.
     *
     * @var string
     */
    protected string $file = '.env';

    /**
     * The variables of the environment.
     *
     * @var array<string, mixed>
     */
    protected array $variables = [];

    /**
     * Register the environment Service into the application and bind the functionality there. so that we have
     * knowledge of all application environment variables with the ability to interact.
     *
     * @return void
     */
    public function register(): void
    {
        $this->bootstrap();

        $this->application->instance(self::class, $this);
    }

    /**
     * Bootstrap the provider.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        $this->loadEnvironmentVariables();
    }

    /**
    *
    */

    /**
     * Set the name of the environment file which will be loaded into memory.
     *
     * @param string $file
     * @return $this
     */
    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get the environment file name.
     *
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * Setting the location of the environment file.
     *
     * @param string $path
     * @return $this
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the environment file location.
     *
     * @param string|null $file
     * @return string
     */
    public function getPath(?string $file = null): string
    {
        return $this->path . $file;
    }

    /**
     * Set a targeted variable within the environment.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, mixed $value = null): void
    {
        // if the key starts with a hash, aka a comment, then we are going to simply ignore this particular set and
        // move on from it as we will no longer want to do anything in particular with it.
        if ($key[0] === '#') {
            return;
        }

        $this->variables[$key] = ! empty($value) ? $value : null;
    }

    /**
     * Get a targeted variable within the environment.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->variables[$key] ?? $default;
    }

    /**
     * Get all variables that are stored within the environment.
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->variables;
    }

    /**
     * Load all the environment variables and instantiate them into the environment in memory for ease of access
     * to the variables that reside within.
     *
     * @return self
     */
    public function loadEnvironmentVariables(): self
    {
        $fileContents = trim((string) file_get_contents($this->getPath($this->getFile())), "\n");

        if (! empty($fileContents)) {
            $environmentEntities = explode(
                "\n",
                (string) preg_replace("/\n+/", "\n", $fileContents)
            );

            foreach ($environmentEntities as $environmentEntity) {
                $this->set(...explode('=', $environmentEntity));
            }
        }

        return $this;
    }

    /**
     * Cache all environment variables to take heat away from the application having to set it all.
     *
     * @return void
     */
    public function cache(): void
    {
        // todo this wants to cache all the .env variables into an array that is then loaded into the applications
        //  memory on boot, instead of parsing. (which should speed up the process).
    }

    /**
     * Synonymous of loadEnvironmentVariables however coined to be utilised in a moment where one needs to reload the
     * environment variables of the application. This method should always be calling the loadEnvironmentVariables()
     * method.
     *
     * @return self
     */
    public function reloadEnvironmentVariables(): self
    {
        return $this->loadEnvironmentVariables();
    }
}
