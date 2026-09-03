<?php

namespace App\Http\Controllers\Api;

use Vyui\Foundation\Http\Request;
use Vyui\Foundation\Http\Response;
use App\Http\Controllers\Api\ApiController as Controller;
use Vyui\Foundation\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * a request to get you.
     *
     * @param Request $request
     * @return Response
     */
    public function me(Request $request): JsonResponse
    {    
        return new JsonResponse(new UserController());
    }
}
