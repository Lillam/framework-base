<?php

namespace Vyui\Services\View\ViewEngines\Vyui;

use Vyui\Services\View\ViewEngines\CompilerContract;

class Compiler implements CompilerContract
{
    protected array $regex = [
        'yield'   => '/( *)#\[yield:(.*)\]/',
        'extends' => '/#\[extends:(.*)\]/',
        'include' => '/#\[include:(.*)\]/',
        'section' => '/(\#\[section:(.*)\])([\S\s]*?)(\#\[\/section\])/',
        'if'      => '/(\#\[if:(.*)\])([\S\s]*?)(\#\[\/if\])/',
        'for'     => '/(\#\[for:(.*)\])([\S\s]*?)(\#\[\/for\])/',
        'foreach' => '/(\#\[foreach: (.*)\])([\S\s]*?)(\#\[\/foreach\])/',
        // 'echo'    => '/#\[echo:(.*)\]/',
        'echo' => '/{{(.*)}}/',
        'uecho' => '/{!!(.*)!!})/',
    ];

    public function compile(string $contents): string
    {
        return $contents;
    }
}
