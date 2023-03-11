<?php

namespace App\Http\Controllers\Api;

use Vyui\Auth\JWT;
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
     * @param JWT $token
     * @return Response
     */
    public function getToken(JWT $token): Response
    {
        return response()->json($token->encode([
            'access_token' => 'abcde12345',
            'refresh_token' => 'abcdef12345',
            'expiry' => 60 * 60 * 24
        ]));
    }

    /**
     * @return Response
     */
    public function refreshToken(): Response
    {
        return new Response('refresh token');
    }
}