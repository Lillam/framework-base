<?php

namespace App\Http\Controllers\Api;

use Exception;
use Vyui\Auth\Token;
use App\Models\User;
use Vyui\Foundation\Http\Request;
use Vyui\Foundation\Http\Response;
use Vyui\Auth\TokenExpiredException;
use App\Http\Controllers\Api\ApiController as Controller;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function login(Request $request): Response
    {
        ['email' => $email, 'password' => $password, 'id' => $id] = $request->all('email', 'password', 'id');

        dd($email, $password, $id, $request, 'from the user controller');

        $user = User::where('email', '=', 'liam.taylor@outlook.com')->first()->__toArray();

        // todo extract into a token service which can create access tokens, refresh tokens etc in a more simplified
        //      more elegant manner that is more re-usable and easier to write than this.
        $token = (new Token)->encode([
            'exp' => time() + 100,
            'user' => $user
        ]);

        return response()->json([
            'token' => $token
        ]);
    }

    /**
     * @return void
     */
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

            (new Token)->decode($token);
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
