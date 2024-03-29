<?php

namespace Vyui\Services\Exceptions;

use Vyui\Services\Service;
use App\Exceptions\Handler;

class ExceptionService extends Service
{
    public function register(): void
    {
        $this->application->singleton(Handler::class, Handler::class);
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
