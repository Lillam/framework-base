<?php

namespace Vyui\Tests\Assertions;

class AssertFloat extends TestAssertion
{
    /**
     * @var string
     */
    protected string $message = "{state} asserting that ({expectedType}) {expected} is a float";

    /**
     * @return bool
     */
    public function evaluate(): bool
    {
        return is_float($this->expected);
    }
}