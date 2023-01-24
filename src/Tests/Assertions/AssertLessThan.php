<?php

namespace Vyui\Tests\Assertions;

class AssertLessThan extends TestAssertion
{
    /**
     * @var string
     */
    protected string $message = "{state} asserting that {expected} is less than {actual}";

    /**
     * @return bool
     */
    public function evaluate(): bool
    {
        return $this->state = $this->expected < $this->actual;
    }
}