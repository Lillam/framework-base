<?php

namespace Vyui\Tests\Assertions;

class AssertLooseEquals extends TestAssertion
{
    /**
     * @var string
     */
    protected string $message = "{state} asserting that {expected} loosely equals {actual}";

    /**
     * @return bool
     */
    public function evaluate(): bool
    {
        return $this->expected == $this->actual;
    }
}