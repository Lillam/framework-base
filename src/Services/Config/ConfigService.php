<?php

namespace Vyui\Services\Config;

use Vyui\Services\Service;
use Vyui\Contracts\Config\Config as ConfigContract;

class ConfigService extends Service
{
    /**
     * The location where you can find the configuration files.
     *
     * @var string
     */
    protected string $path = '/config/';

    /**
     * Register the config instance into the application so that it's possible for the application to interact with all
     * the configurations of the application via files within a particular directory.
     *
     * @return void
     */
    public function register(): void
    {
        $this->application->instance(ConfigContract::class, (new Config)
            ->setPath($this->application->getBasePath($this->path))
            ->loadConfigurations()
        );
    }

    /**
     * Bootstrap the provider.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        $this->bootstrapped = true;
    }
}
