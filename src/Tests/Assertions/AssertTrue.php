<?php

namespace Vyui\Tests\Assertions;

class AssertTrue extends TestAssertion
{
    protected string $message = "{state} asserting that {expected} is true";

    public function evaluate(): bool
    {
        return $this->state = $this->expected === true;
    }
}