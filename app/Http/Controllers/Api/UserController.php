<?php

namespace App\Http\Controllers\Api;

use Vyui\Auth\JWT;
use App\Models\User;
use Vyui\Contracts\Application;
use Vyui\Foundation\Http\Request;
use Vyui\Foundation\Http\Response;
use Vyui\Auth\TokenExpiredException;
use App\Http\Controllers\Api\ApiController as Controller;

class UserController extends Controller
{
    public function __construct()
    {
//        $apiKey = str_replace(
//            'api-key=',
//            '',
//            request()->getHeader('http_authorization')
//        );
//
//        if (! User::where('api_token', '=', $apiKey)->first()) {
//            http_response_code(401);
//            echo json_encode(['message' => 'Invalid api-key']);
//            exit;
//        }
    }

    public function login(Request $request): Response
    {
        dd($request->getRequest());

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

        // return response()->json(['token' => $token, 'user' => $user]);
    }

    public function index(Request $request): Response
    {
        dd($request->getQuery());

        return response()->json(['message' => 'From Index within the UserController class']);

//        try {
//            $token = $request->get('token') ??
//                     str_replace(['Bearer', ' '], '', $request->getHeader('http_authorization'));
//
//            (new JWT)->decode($token);
//        }
//
//        catch (TokenExpiredException) {
//            return response()->json(['error' => 'token is expired']);
//        }
//
//        catch (\Exception) {
//            return response()->json([ 'error' => 'token is invalid' ]);
//        }
//
//        return response()->json(array_map(function ($item) {
//            return $item->getAttributes();
//        }, User::all()));
    }
}