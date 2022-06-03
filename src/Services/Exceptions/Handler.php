<?php

namespace Vyui\Services\Exceptions;

use Exception;
use Throwable;
use Vyui\Foundation\Http\Response;
use Vyui\Contracts\Exceptions\Handler as HandlerContract;

class Handler implements HandlerContract
{
	/**
	 * @param Exception|Throwable|int $exception
	 * @return Response
	 */
    public function render(Exception|Throwable|int $exception): Response
    {
		return view('exceptions', [
			'exception' => $exception
		])->send();
    }
}