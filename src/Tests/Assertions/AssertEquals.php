<?php

namespace Vyui\Tests\Assertions;

class AssertEquals extends TestAssertion
{
    /**
     * @var string
     */
    protected string $message = "{state} asserting that ({expectedType}) {expected} equals ({actualType}) {actual}";

    /**
     * @return bool
     */
    public function evaluate(): bool
    {
        return $this->expected === $this->actual;
    }
}