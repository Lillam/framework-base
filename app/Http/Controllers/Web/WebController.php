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

//        return response()->json(array_map(function ($item) {
//            return $item->getAttributes();
//        }, User::all()));

        return response()->json(array_map(function ($user) {
            return [
                'name' => "{$user->getFirstName()} {$user->getLastName()}"
            ];
        }, User::all()));
	}

	public function test(Request $request, $testing, $test): Response
	{
		return view('home2', [
			'some_data' => [
				'test' => $test,
				'testing' => $testing
			]
		]);
	}
}