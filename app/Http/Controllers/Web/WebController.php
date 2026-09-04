<?php

namespace App\Http\Controllers\Web;

use Vyui\Auth\Token;
use App\Models\Task;
use Vyui\Foundation\Http\Request;
use Vyui\Foundation\Http\Response;
use Vyui\Foundation\Http\Controller;
use Vyui\Foundation\Http\Middleware\{CORS, Authenticate};

class WebController extends Controller
{
    protected array $middleware = [
        Authenticate::class,
        CORS::class,
    ];

    /**
     * @param Request $request
     * @param Task|null $task
     * @return Response
     */
    public function home(Request $request, ?Task $task = null): Response
    {
        return view('home');
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

    public function giveToken(Request $request): Response
    {
        $token = (new Token)->encode([
            'exp' => time() + 10000,
            'user' => ['name' => 'John Doe']
        ]);

        return response()->json([
            'token' => $token
        ]);
    }

    /**
     * @param Request $request
     * @return Response
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
