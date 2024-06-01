<?php

namespace Vyui\Services\View\ViewEngines\Vyui;

use Vyui\Services\View\ViewEngines\CompilerContract;

class Compiler implements CompilerContract
{
    public function compile(string $contents): string
    {
        return $contents;
    }
}
