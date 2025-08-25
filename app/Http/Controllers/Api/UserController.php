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
        ["email" => $email, "password" => $password, "id" => $id] = $request->all("email", "password", "id");

        $user = User::where("email", "=", $email)->first()->__toArray();

        // todo extract into a token service which can create access tokens, refresh tokens etc in a more simplified
        //      more elegant manner that is more re-usable and easier to write than this.
        $token = (new Token())->encode([
            "exp" => time() + 10000,
            "user" => $user,
        ]);

        return response()->json([
            "token" => $token,
            "header" => $request->getAuthorization(),
        ]);
    }

    /**
     * @return void
     */
    public function random(): void
    {
        $apiKey = str_replace("api-key=", "", request()->getHeader("HTTP_AUTHORIZATION"));

        // if (!User::where("api_token", "=", $apiKey)->first()) {
        //     http_response_code(401);
        //     echo json_encode(["message" => "Invalid api-key"]);
        //     exit();
        // }
    }

    /**
     * ...
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        // not the best of implementations here... this would be better behind it's own
        // middleware for the request... if the bearer exists within the headers then we
        // can map it to the request object instead of doing this dance.
        // for every single time we're wanting to extract the bearer.
        try {
            $token = $request->getAuthorization("Bearer");

            (new Token())->decode($token);
        }

        catch (TokenExpiredException) {
            return response()->json(["error" => "token is expired"]);
        }

        catch (Exception) {
            return response()->json(["error" => "token is invalid"]);
        }

        return response()->json(array_map(
            fn($item) => $item->getAttributes(),
            User::all()
        ));
    }
}
