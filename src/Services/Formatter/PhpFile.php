<?php

namespace Vyui\Services\Formatter;

use SplFileObject;

class PhpFile
{
    protected SplFileObject $file;

    public function __construct(SplFileObject $file)
    {
        $this->file = $file;
    }
}
