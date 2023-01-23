<?php

namespace Vyui\Tests\Assertions;

class AssertEmpty extends TestAssertion
{
    protected string $message = "{state} asserting that expected is empty";

    public function evaluate(): bool
    {
        return $this->state = empty($this->expected);
    }
}