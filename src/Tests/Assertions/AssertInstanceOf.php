<?php

namespace Vyui\Tests\Assertions;

class AssertInstanceOf extends TestAssertion
{
    /**
     * @var string
     */
    protected string $message = "{state} asserting that expected is an instance of {actual}";

    /**
     * @return bool
     */
    public function evaluate(): bool
    {
        return $this->state = $this->expected instanceof $this->actual;
    }
}