<?php

namespace Vyui\Tests\Assertions;

class AssertCount extends TestAssertion
{
    /**
     * @var string
     */
    protected string $message = "{state} asserting array has a count of {expected}";

    /**
     * @return bool
     */
    public function evaluate(): bool
    {
        return $this->expected === count($this->actual);
    }
}