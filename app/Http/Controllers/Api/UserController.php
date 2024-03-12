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

        if (!User::where("api_token", "=", $apiKey)->first()) {
            http_response_code(401);
            echo json_encode(["message" => "Invalid api-key"]);
            exit();
        }
    }

    public function index(Request $request): Response
    {
        try {
            $token = $request->getAuthorization("Bearer");

            (new Token())->decode($token);
        } catch (TokenExpiredException) {
            return response()->json(["error" => "token is expired"]);
        } catch (Exception) {
            return response()->json(["error" => "token is invalid"]);
        }

        return response()->json(
            array_map(function ($item) {
                return $item->getAttributes();
            }, User::all())
        );
    }
}

/**
curl -H "Content-Type: application/json" \
-H "Authorization: Bearer {}" \
-X GET \
https://framework.test/api/v1/users

curl -H "Content-Type: application/json" \
-d '{ "email": "name@name.email.com", "password": "this is a password" }' \
-X POST \
https://framework.test/api/v1/login


curl -H "Content-Type: application/json" \
-H "Authorization: Bearer eyJ0eXAiOiJUb2tlbiIsImFsZyI6IkhTMjU2In0.eyJhY2Nlc3NfdG9rZW4iOiJhYmNkZTEyMzQ1IiwicmVmcmVzaF90b2tlbiI6ImFiY2RlZjEyMzQ1IiwiZXhwaXJ5Ijo4NjQwMH0.iRKaFskQ0Qa1Zz-hR2zRiYJGrHT6neFmJcwTqvpI4i4" \
-d '{ "email": "name@name.email.com", "password": "this is a password" }' \
-X POST \
https://framework.test/api/v1/login
 */
