<?php

namespace Vyui\Foundation\Http;

use Exception;
use Vyui\Foundation\Http\Request;
use Vyui\Foundation\Http\Response;

interface KernelContract
{
    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function handle(Request $request): Response;
}
