<?php

namespace Vyui\Tests\Assertions;

class AssertNotNull extends TestAssertion
{
    /**
     * @var string
     */
    protected string $message = "{state} asserting that expected is not null";

    /**
     * @return bool
     */
    public function evaluate(): bool
    {
        return $this->state = ! is_null($this->expected);
    }
}