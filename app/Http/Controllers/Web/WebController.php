<?php

namespace App\Http\Controllers\Web;

use App\Models\Task;
use App\Models\User;
use Vyui\Foundation\Http\Request;
use Vyui\Foundation\Http\Response;
use Vyui\Foundation\Http\Controller;

class WebController extends Controller
{
    /**
     * @param Request $request
     * @param Task|null $task
     * @return Response
     */
    public function index(Request $request, ?Task $task = null): Response
    {
        return response()->json(array_map(function ($user) use ($task) {
            return [
                'name' => "{$user->getFirstName()} {$user->getLastName()}",
                'task' => $task
            ];
        }, User::all()));
    }

    public function test(Request $request, $test, $testing): Response
    {
        return view('home3', [
            'some_data' => [
                'test' => $test,
                'testing' => $testing
            ]
        ]);
    }
}