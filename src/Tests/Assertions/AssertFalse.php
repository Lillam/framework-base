<?php

namespace Vyui\Tests\Assertions;

class AssertFalse extends TestAssertion
{
    protected string $message = "{state} asserting that {expected} is false";

    public function evaluate(): bool
    {
        return $this->state = $this->expected === false;
    }
}