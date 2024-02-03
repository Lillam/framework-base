<?php

namespace Vyui\Services\Auth;

use Vyui\Auth\Token;
use Vyui\Services\Service;

class AuthService extends Service
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->application->instance(Token::class, new Token);
    }

    public function bootstrap(): void
    {
        $this->bootstrapped = true;
    }
}