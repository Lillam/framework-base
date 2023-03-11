<?php

namespace Vyui\Services\Auth;

use Vyui\Auth\JWT;
use Vyui\Services\Service;

class AuthService extends Service
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->application->instance(JWT::class, new JWT);
    }

    public function bootstrap(): void
    {
        $this->bootstrapped = true;
    }
}