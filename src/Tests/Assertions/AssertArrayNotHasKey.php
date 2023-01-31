<?php

namespace Vyui\Tests\Assertions;

class AssertArrayNotHasKey extends TestAssertion
{
    /**
     * @var string
     */
    protected string $message = "{state} asserting that {actualType} does not have key {expected}";

    /**
     * Check to see if the actual array has the key of expected.
     *
     * @return bool
     */
    public function evaluate(): bool
    {
        return ! array_key_exists($this->expected, $this->actual);
    }
}