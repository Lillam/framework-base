<?php

namespace App\Http\Controllers\Api;

use Vyui\Auth\Token;
use Vyui\Foundation\Http\Response;
use Vyui\Foundation\Http\Controller;

class ApiController extends Controller
{
    /**
     * @var array|string[]
     */
    protected array $middleware = [
        \Vyui\Foundation\Http\Middleware\Authenticate::class
    ];

    /**
     * @param Token $token
     * @return Response
     */
    public function getToken(Token $token): Response
    {
        return $this->respond([
            "token" => $token->encode([
                "access_token" => "abcde12345",
                "refresh_token" => "abcdef12345",
                "expiry" => 60 * 60 * 24,
            ]),
        ]);
    }

    /**
     * @return Response
     */
    public function refreshToken(): Response
    {
        return new Response("refresh token");
    }
}
