<?php

namespace Vyui\Tests\Assertions;

class AssertInt extends TestAssertion
{
    /**
     * @var string
     */
    protected string $message = "{state} asserting that {expectedType} {execpeted} is an int";

    /**
     * @return bool
     */
    public function evaluate(): bool
    {
        return is_int($this->expected);
    }
}
