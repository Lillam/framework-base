<?php

namespace Vyui\Tests\Assertions;

class AssertTrue extends TestAssertion
{
    /**
     * @var string
     */
    protected string $message = "{state} asserting that {expected} is true";

    /**
     * @return bool
     */
    public function evaluate(): bool
    {
        return $this->expected === true;
    }
}
