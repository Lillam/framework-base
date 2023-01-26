<?php

namespace Vyui\Tests\Assertions;

class AssertEmpty extends TestAssertion
{
    /**
     * @var string
     */
    protected string $message = "{state} asserting that expected ({expectedType}) is empty";

    /**
     * @return bool
     */
    public function evaluate(): bool
    {
        return empty($this->expected);
    }
}