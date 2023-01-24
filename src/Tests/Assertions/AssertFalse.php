<?php

namespace Vyui\Tests\Assertions;

class AssertFalse extends TestAssertion
{
    /**
     * @var string
     */
    protected string $message = "{state} asserting that {expected} is false";

    /**
     * @return bool
     */
    public function evaluate(): bool
    {
        return $this->state = $this->expected === false;
    }
}