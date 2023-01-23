<?php

namespace Vyui\Tests\Assertions;

class AssertEquals extends TestAssertion
{
    protected string $message = "{state} asserting that {expected} equals {actual}";

    public function evaluate(): bool
    {
        return $this->state = $this->expected === $this->actual;
    }
}