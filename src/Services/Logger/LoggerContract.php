<?php

namespace Vyui\Services\Logger;

interface LoggerContract
{
    public function log(string $contents): static;
}
