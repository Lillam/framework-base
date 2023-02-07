<?php

namespace App\Http\Controllers\Api;

use Exception;
use Vyui\Auth\JWT;
use App\Models\User;
use Vyui\Foundation\Http\Request;
use Vyui\Foundation\Http\Response;
use Vyui\Auth\TokenExpiredException;
use App\Http\Controllers\Api\ApiController as Controller;

class UserController extends Controller
{
    public function login(Request $request): Response
    {
        $email    = $request->get('email');
        $password = $request->get('password');
        $user = User::where('email', '=', 'liam.taylor@outlook.com')->first()->__toArray();

        $token = (new JWT)->encode([
            'exp' => time() + 100,
            'user' => $user
        ]);

        return response()->json([
            'token' => $token
        ]);
    }

    public function random()
    {
        $apiKey = str_replace(
            'api-key=',
            '',
            request()->getHeader('http_authorization')
        );

        if (! User::where('api_token', '=', $apiKey)->first()) {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid api-key']);
            exit;
        }
    }

    public function index(Request $request): Response
    {
        try {
            $token = $request->get('token') ??
                     str_replace(['Bearer', ' '], '', $request->getHeader('http_authorization'));

            (new JWT)->decode($token);
        }

        catch (TokenExpiredException) {
            return response()->json(['error' => 'token is expired']);
        }

        catch (Exception) {
            return response()->json([ 'error' => 'token is invalid' ]);
        }

        return response()->json(array_map(function ($item) {
            return $item->getAttributes();
        }, User::all()));
    }
}