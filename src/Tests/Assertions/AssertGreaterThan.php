<?php

namespace Vyui\Tests\Assertions;

class AssertGreaterThan extends TestAssertion
{
    /**
     * @var string
     */
    protected string $message = "{state} asserting that {expected} is greater than {actual}";

    /**
     * @return bool
     */
    public function evaluate(): bool
    {
        return $this->state = $this->expected > $this->actual;
    }
}