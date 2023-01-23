<?php

namespace Vyui\Tests\Assertions;

class AssertGreaterThan extends TestAssertion
{
    protected string $message = "{state} asserting that {expected} is greater than {actual}";

    public function evaluate(): bool
    {
        return $this->state = $this->expected > $this->actual;
    }
}