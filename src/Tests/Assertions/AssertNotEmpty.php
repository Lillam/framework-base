<?php

namespace Vyui\Tests\Assertions;

class AssertNotEmpty extends TestAssertion
{
    protected string $message = "{state} asserting that expected is not empty";

    public function evaluate(): bool
    {
        return $this->state = ! empty($this->expected);
    }
}