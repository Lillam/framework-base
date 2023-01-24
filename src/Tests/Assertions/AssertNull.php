<?php

namespace Vyui\Tests\Assertions;

class AssertNull extends TestAssertion
{
    /**
     * @var string
     */
    protected string $message = "{state} asserting that expected is null";

    /**
     * @return bool
     */
    public function evaluate(): bool
    {
        return $this->state = is_null($this->expected);
    }
}