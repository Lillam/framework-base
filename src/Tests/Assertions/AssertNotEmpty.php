<?php

namespace Vyui\Tests\Assertions;

class AssertNotEmpty extends TestAssertion
{
    /**
     * @var string
     */
    protected string $message = "{state} asserting that expected is not empty";

    /**
     * @return bool
     */
    public function evaluate(): bool
    {
        return ! empty($this->expected);
    }
}