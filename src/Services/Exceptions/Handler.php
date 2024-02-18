<?php

namespace Vyui\Services\Exceptions;

use Throwable;
use Vyui\Foundation\Http\Response;
use Vyui\Contracts\Exceptions\Handler as HandlerContract;

class Handler implements HandlerContract
{
    /**
     * @param Throwable|int $exception
     * @return Response
     */
    public function render(Throwable|int $exception): Response
    {
        return view('exceptions', [
            'exception' => $exception
        ])->send();
    }
}
