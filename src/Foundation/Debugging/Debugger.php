<?php

namespace Vyui\Foundation\Debugging;

class Debugger
{
    public function backtrack(): array
    {
        return debug_backtrace();
    }
}
