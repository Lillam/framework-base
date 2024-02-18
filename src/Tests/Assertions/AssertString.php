<?php

namespace Vyui\Tests\Assertions;

class AssertString extends TestAssertion
{
    /**
     * @var string
     */
    protected string $message = "{state} asserting that ({expectedType}) {expected} is a string";

    /**
     * @return bool
     */
    public function evaluate(): bool
    {
        return is_string($this->expected);
    }
}
