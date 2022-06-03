<?php

namespace App\Http\Controllers;

use Vyui\Foundation\Http\Request;
use Vyui\Foundation\Http\Response;
use Vyui\Services\Routing\Controller as BaseHttpController;

class Controller extends BaseHttpController
{
	/**
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request): Response
	{
		return view('home');
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