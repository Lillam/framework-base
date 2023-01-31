<?php

namespace Vyui\Services\Config;

use Vyui\Support\Helpers\Arrayable;
use Vyui\Contracts\Config\Config as ConfigContract;

class Config implements ConfigContract
{
    /**
     * @var string
     */
    protected string $path;

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
     * Setting the location of the config files.
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
     * Getting the location of hte config files.
     *
     * @param string|null $file
     * @return string
     */
    public function getPath(?string $file = null): string
    {
        return $this->path . $file;
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
        // if there is no directory for the configs, then we're going to make the directory, the directory would be an
        // important part of the system and if it's not present, we're going to need to make it present.
        if (! is_dir($directory = $this->getPath())) {
            mkdir($directory);
        }

        $files = array_diff(scandir($directory), ['.', '..']);

        // todo - check to see whether or not we have already cached the results of the configurations, and if we have
        //        load that particular file instead of iterating over them. here we are going to overwrite the files
        //        variable for ease of development without overwriting necessary components.

        // iterate over all the config files. so that we can begin placing them into storage of the application. this
        // will provide a later ease of access to the necessary configurations without having to keep re-opening files
        foreach ($files as $file) {
            $this->set(
                str_replace('.php', '', $file),
                require_once $this->getPath($file)
            );
        }

        if ($this->isFlattened) {
            $this->configs = (new Arrayable($this->configs))->flatten(true)
                                                            ->toArray();
        }

        return $this;
    }
}