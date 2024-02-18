<?php

namespace Vyui\Tests\Assertions;

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
     * @return string
     */
    public function getMessage(): string
    {
        return preg_replace_callback_array([
            '/\{state\}/'        => fn () => $this->getStateMessage(),
            '/\{expected\}/'     => fn () => $this->getExpectedValue(),
            '/\{actual\}/'       => fn () => $this->getActualValue(),
            '/\{expectedType\}/' => fn () => $this->getExpectedValueType(),
            '/\{actualType\}/'   => fn () => $this->getActualValueType()
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
     * @param bool $state
     * @return $this
     */
    public function setState(bool $state): static
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpectedValue(): mixed
    {
        return $this->expected;
    }

    /**
     * @return string
     */
    public function getExpectedValueType(): string
    {
        return gettype($this->expected);
    }

    /**
     * @return mixed
     */
    public function getActualValue(): mixed
    {
        return $this->actual;
    }

    /**
     * @return string
     */
    public function getActualValueType(): string
    {
        return gettype($this->actual);
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
