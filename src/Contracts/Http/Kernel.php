<?php

namespace Vyui\Contracts\Http;

use Exception;
use Vyui\Foundation\Http\Request;
use Vyui\Foundation\Http\Response;

interface Kernel
{
    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function handle(Request $request): Response;
}
