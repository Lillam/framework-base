<?php

namespace Vyui\Services\Environment;

use Vyui\Services\Service;
use Vyui\Contracts\Environment\Environment as EnvironmentContract;

class EnvironmentService extends Service
{
	/**
	 * The location where you can find the environment file (.env).
	 *
	 * @var string
	 */
	protected string $path = '/';

    /**
     * Register the environment Service into the application and bind the functionality there. so that we have
     * knowledge of all application environment variables with the ability to interact.
     *
     * @return void
     */
    public function register(): void
    {
        $this->application->instance(
            EnvironmentContract::class,
            (new Environment)->setPath($this->application->getBasePath($this->path))
        );

        $this->bootstrap();
    }

    /**
     * Bootstrap the provider.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        $this->application->make(EnvironmentContract::class)->loadEnvironmentVariables();

        $this->bootstrapped = true;
    }
}