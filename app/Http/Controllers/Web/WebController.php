<?php

namespace App\Http\Controllers\Web;

use Vyui\Auth\Token;
use App\Models\Task;
use Vyui\Foundation\Http\Request;
use Vyui\Foundation\Http\Response;
use Vyui\Foundation\Http\Controller;
use Vyui\Foundation\Http\Middleware\Authenticate;

class WebController extends Controller
{
    protected array $middleware = [Authenticate::class];

    /**
     * @param Request $request
     * @param Task|null $task
     * @return Response
     */
    public function index(Request $request, ?Task $task = null): Response
    {
        return view('home3');

//        return response()->json(array_map(function ($user) use ($task) {
//            return [
//                'name' => "{$user->getFirstName()} {$user->getLastName()}",
//                'task' => $task
//            ];
//        }, User::all()));
    }

    public function test(Request $request, $test, $testing): Response
    {
        return view('home3', [
            'some_data' => (object) [
                'test' => "this is a trap",
                'testing' => "right?"
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Vyui\Auth\TokenExpiredException
     * @throws \Vyui\Auth\TokenInvalidException
     * @throws \Vyui\Auth\TokenSignatureMatchException
     */
    public function parseToken(Request $request): Response
    {
        try {
            $decoded = (new Token)->decode($request->get('token'));
        }

        catch (\Throwable $throwable) {
            $decoded = ['error' => $throwable->getMessage()];
        }

        return response()->json($decoded);
    }
}
