<?php

namespace Vyui\Tests\Assertions;

class AssertArrayHasKey extends TestAssertion
{
    /**
     * @var string
     */
    protected string $message = "{state} asserting {actualType} has key {expected}";

    /**
     * Evaluate whether actual has the array of key of expected.
     *
     * @return bool
     */
    public function evaluate(): bool
    {
        return array_key_exists($this->expected, $this->actual);
    }
}
