<?php

namespace Vyui\Services\Logger;

use Vyui\Services\Service;

class LogService extends Service
{
    /**
     * Register the service into the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->application->singleton(LoggerContract::class, Logger::class);
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
