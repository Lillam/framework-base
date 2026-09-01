<?php

namespace Vyui\Services\Config;

use Vyui\Services\Service;

class ConfigService extends Service
{
    private array $store = [];

    private string $path {
        get => $this->application->getBasePath("/config/*.php");
    }

    public function register(): void
    {
        // bootstrap the service before registering it into the application
        // allow the time for the configuration details be built and loaded
        // into the store before it is registered into the application.
        $this->bootstrap();

        $this->application->instance(self::class, $this);
    }

    /**
     * Bootstrap the service and get all the config variables from the
     * directory
     *
     * @return void
     */
    public function bootstrap(): void
    {
        foreach (glob($this->path) as $file) {
            $this->store[str_replace('.php', '', basename($file))] = require $file;
        }
    }

    public function get(string $keys, mixed $default = null): mixed
    {
        $keys = explode('.', $keys);

        $value = $this->store;

        foreach ($keys as $key) {
            $value = $value[$key] ?? null;
        }

        return $value ?? $default;
    }

    public function all(): array
    {
        return $this->store;
    }

    /**
     * this function will wllow the user to call the following:
     * ->config()->app('path')
     * ->config()->database('host')
     * ->config()->filesystem('driver')
     * for example, allowing the developer to call specific config values with ease.
     *
     * @param array<mixed> $args
     */
    public function __call(string $method, array $args): mixed
    {
        // if the first passed argument is set, has a . (dot) separated key
        // then we're going to simply jump into the get() method and proxy
        // into there instead of processing with the rest of the work.
        if (isset($args[0]) && \str_contains($args[0], '.')) {
            return $this->get($method !== 'get' ? $method . '.' : '' . $args[0]);
        }

        if (! \array_key_exists($method, $this->store)) {
            return null;
        }

        // if we're dealing with the single method ->app() | ->database() then
        // return the entire store for that metohd in particular.
        if (!isset($args[0]) || !\array_key_exists($args[0], $this->store[$method])) {
            return $this->returnValueOrConfigStoreValue($this->store[$method] ?? null);
        }

        return $this->returnValueOrConfigStoreValue($this->store[$method][$args[0]] ?? null);
    }

    private function returnValueOrConfigStoreValue(mixed $value): mixed
    {
        return \is_array($value) ? new ConfigStoreValue($value) : $value;
    }
}
