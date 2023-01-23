<?php

namespace Vyui\Tests\Assertions;

class AssertLessThan extends TestAssertion
{
    protected string $message = "{state} asserting that {expected} is less than {actual}";

    public function evaluate(): bool
    {
        return $this->state = $this->expected < $this->actual;
    }
}