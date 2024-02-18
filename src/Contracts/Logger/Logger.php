<?php

namespace Vyui\Contracts\Logger;

interface Logger
{
    public function log(string $contents): static;
}
