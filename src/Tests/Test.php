<?php

namespace Vyui\Tests;

use BadMethodCallException;
use Vyui\Support\Helpers\_String;

// use Vyui\Support\Helpers\_String;
// use Vyui\Tests\Assertions\TestAssertion;

abstract class Test
{
    protected string $name;

    protected int $passed = 0;
    protected int $failed = 0;

    /**
     * The tests in which have passed.
     *
     * @var AssertionCollection
     */
    protected AssertionCollection $assertions;

    /**
     * Upon object instantiation we're going to construct this particular item with the necessary collection items.
     *
     * @param AssertionCollection|null $assertions
     * @return void
     */
    public function __construct(?AssertionCollection $assertions)
    {
        $this->assertions = $assertions ?? new AssertionCollection();
    }

    public function assert(mixed $value = null): Assertion
    {
        return new Assertion($this, $value);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAssertions(): AssertionCollection
    {
        return $this->assertions;
    }

    public function passedAssertions(): int
    {
        return $this->passed;
    }

    public function failedAssertions(): int
    {
        return $this->failed;
    }

    public function passFail(bool $pass): void
    {
        if ($pass) {
            $this->passed++;
            return;
        }

        $this->failed++;
    }

    public function totalAssertions(): int
    {
        return $this->passed + $this->failed;
    }

    public function hasPassed(): bool
    {
        return $this->passedAssertions() === $this->totalAssertions();
    }

    public function __call(string $method, array $parameters = []): void
    {
        $this->name = _String::fromString($method)->convertCamelCaseToSentence();

        if (! method_exists($this, $method)) {
            throw new BadMethodCallException("Method [$method] does not exist within " . static::class);
        }

        $this->{$method}(...$parameters);
    }

    public function __toString(): string
    {
        return static::class;
    }
}
