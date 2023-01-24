<?php

namespace Vyui\Tests\Assertions;

use Vyui\Support\Helpers\_String;

abstract class TestAssertion
{
    /**
     * State of the current assertion
     *
     * @var bool
     */
    protected bool $state = false;

    /**
     * The actual value we're expecting to be receiving back.
     *
     * @var mixed
     */
    protected mixed $expected;

    /**
     * The value that we're comparing against, this particular value isn't always going to be used amongst assertions
     * and thus is allowed nullable
     *
     * @var mixed|null
     */
    protected mixed $actual;

    /**
     * The assertions message.
     *
     * @var string
     */
    protected string $message = "";

    /**
     * @param mixed $expected
     * @param mixed|null $actual
     */
    public function __construct(mixed $expected, mixed $actual = null)
    {
        $this->expected = $expected;
        $this->actual   = $actual;
    }

    /**
     * Eveluation of the assertion which will return either true or false depending on whether the test had passed or
     * not.
     *
     * @return bool
     */
    abstract public function evaluate(): bool;

    /**
     * Get the message for the assertion upon eveluation. which will essentially act as an output buffer.
     *
     * @param bool $withType
     * @return string
     */
    public function getMessage(bool $withType = false): string
    {
        return preg_replace_callback_array([
            '/\{state\}/'    => fn () => $this->getStateMessage(),
            '/\{expected\}/' => fn () => $this->getExpectedValue($withType),
            '/\{actual\}/'   => fn () => $this->getActualValue($withType),
        ], $this->message);
    }

    /**
     * @return bool
     */
    public function getState(): bool
    {
        return $this->state;
    }

    /**
     * @param bool $withType
     * @return mixed
     */
    public function getExpectedValue(bool $withType = false): mixed
    {
        return $this->expected;
    }

    /**
     * @param bool $withType
     * @return mixed
     */
    public function getActualValue(bool $withType = false): mixed
    {
        return $this->actual;
    }

    /**
     * @return string
     */
    public function getStateMessage(): string
    {
        return $this->state ? 'Successfully'
                            : 'Unsuccessfully';
    }
}