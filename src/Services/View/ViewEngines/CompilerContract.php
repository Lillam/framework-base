<?php

namespace Vyui\Services\View\ViewEngines;

interface CompilerContract
{
    // public function preCompile(string $contents): string;
    public function compile(string $contents): string;
}
