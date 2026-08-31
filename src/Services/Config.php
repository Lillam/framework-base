<?php

namespace Vyui\Services;

use Vyui\Services\Service;
use Vyui\Support\Helpers\Arrayable;

class Config extends Service
{
    /**
     * The location where you can find the configuration files.
     *
     * @var string
     */
    protected string $path {
        get => $this->application->getBasePath("/config");
    }

    /**
     * Are the configs cached into the application.
     *
     * @var bool
     */
    protected bool $isCached = false;

    /**
     * Are the configs flattened into the application? i,e, ['config1' => 'config2' => 'config3'] is flattened to
     * ['config1.config2.config3' => 'value'] on a single level of array.
     *
     * @var bool
     */
    protected bool $isFlattened = false;

    /**
     * The configurations that will be loaded into memory.
     *
     * @var array<string, mixed>
     */
    protected array $configs = [];

    /**
     * Register the config instance into the application so that it's possible for the application to interact with all
     * the configurations of the application via files within a particular directory.
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
        $this->loadConfigurations();
    }

    /**
     * Set a config within the application's configuration bank.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set(string $key, mixed $value): self
    {
        $this->configs[$key] = $value;

        return $this;
    }

    /**
     * Get a set config from the application's configuration bank.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        if (! $this->isFlattened && str_contains($key, '.')) {
            $keys = explode('.', $key);
            $configs = null;
            foreach ($keys as $increment => $key) {
                // this foreach realistically only wants to happen if there is a valid key to begin with, otherwise
                // we're going to want to just bail out instantly, there's no real reason to continue looking. this
                // realistically only wants to happen on the very first iteration as a fail-safe.
                if (! $increment && ! isset($this->configs[$key])) {
                    return $default;
                }

                // if the configs hasn't been defined yet, we're going to want to define it and begin appending to this
                // entity, and keep resetting what the new return value should be so that we can chain it down and
                // return the one value, and fail-safe to $default.
                if (! isset($configs[$key])) {
                    $configs = $this->configs[$key] ?? $default;
                    continue;
                }

                // set the configs variable to something new, so that on the next iteration we're beginning to slowly
                // trim this down to its final key.
                $configs = $configs[$key] ?? $default;
            }

            return $configs;
        }

        return $this->configs[$key] ?? $default;
    }

    /**
     * Load all the configurations in the system.
     *
     * @return $this
     */
    public function loadConfigurations(): self
    {
        \is_dir($this->path) || \mkdir($this->path, 0755, true);

        foreach (\glob("{$this->path}/*") as $file) {
            $parts = explode("/", $file);
            $filename = end($parts);
            $this->set(str_replace('.php', '', $filename), require_once $file);
        }

        if ($this->isFlattened) {
            $this->configs = (new Arrayable($this->configs))->flatten(true)
                                                            ->toArray();
        }

        return $this;
    }
}
