<?php

namespace Vyui\Services\Facades;

use Vyui\Services\Service;

class FacadeService extends Service
{
	/**
	 * Register the Facade, setting the facade's application to the one against the service.
	 *
	 * @return void
	 */
    public function register(): void
    {
        Facade::setFacadeApplication($this->application);
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