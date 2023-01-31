<?php

namespace Vyui\Tests\Assertions;

class AssertArray extends TestAssertion
{
    /**
     * @var string
     */
    protected string $message = "{state} asserting that ({expectedType}) is array";

    /**
     * @return bool
     */
    public function evaluate(): bool
    {
        return is_array($this->expected);
    }
}