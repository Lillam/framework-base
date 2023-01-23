<?php

namespace Vyui\Tests\Assertions;

class AssertLooseEquals extends TestAssertion
{
    protected string $message = "{state} asserting that {expected} loosely equals {actual}";

    public function evaluate(): bool
    {
        return $this->state = $this->expected == $this->actual;
    }
}