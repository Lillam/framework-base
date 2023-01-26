<?php

namespace Vyui\Tests\Assertions;

class AssertStdObject extends TestAssertion
{
    /**
     * @var string
     */
    protected string $message = "{state} asserting that the ({expectedType}) {expected} is an stdobject";

    /**
     * @return bool
     */
    public function evaluate(): bool
    {
        return is_object($this->expected);
    }
}