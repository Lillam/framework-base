<?php

namespace Vyui\Tests\Assertions;

class AssertBool extends TestAssertion
{
    /**
     * @var string
     */
    protected string $message = "{state} asserting that ({expectedType}) {expected} is a boolean";

    /**
     * @return bool
     */
    public function evaluate(): bool
    {
        return is_bool($this->expected);
    }
}
