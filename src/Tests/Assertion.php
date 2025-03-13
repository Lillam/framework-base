<?php

namespace Vyui\Tests;

class Assertion
{
    protected Test $test;
    protected mixed $expected;
    protected mixed $actual;
    protected string $description = "";
    protected AssertionType $type;
    protected bool $state = false;

    public function __construct(Test $test, mixed $expected)
    {
        $this->test = $test;
        $this->expected = $expected;
    }

    public function getDescription($prefix = ""): string
    {
        return ($this->description !== "" ? $prefix : "") . $this->description;
    }

    public function description(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function equals(mixed $actual): static
    {
        return $this->solidify($actual, AssertionType::EQUALS);
    }

    public function equalsLoosely(mixed $actual): static
    {
        return $this->solidify($actual, AssertionType::EQUALS_LOOSELY);
    }

    public function greaterThan(mixed $actual): static
    {
        return $this->solidify($actual, AssertionType::GREATER_THAN);
    }

    public function graterThanOrEqual(mixed $actual): static
    {
        return $this->solidify($actual, AssertionType::GREATER_THAN_OR_EQUAL);
    }

    public function lessThan(mixed $actual): static
    {
        return $this->solidify($actual, AssertionType::LESS_THAN);
    }

    public function lessThanOrEqual(mixed $actual): static
    {
        return $this->solidify($actual, AssertionType::LESS_THAN_OR_EQUAL);
    }

    public function isNull(): static
    {
        return $this->solidify($this->expected, AssertionType::IS_NULL);
    }

    public function isNotNull(): static
    {
        return $this->solidify($this->expected, AssertionType::IS_NOT_NULL);
    }

    public function isEmpty(): static
    {
        return $this->solidify($this->expected, AssertionType::EMPTY);
    }

    public function hasPassed(): bool
    {
        return $this->state;
    }

    public function hasFailed(): bool
    {
        return !$this->state;
    }

    public function getMessage(string $prefix = ""): string
    {
        return $prefix . match ($this->type) {
            AssertionType::EQUALS => "expected [$this->expected] to equal [$this->actual]",
            AssertionType::EQUALS_LOOSELY => "expected value to loosely equal [...]",
            AssertionType::GREATER_THAN => "expected value to be greater than [...]",
            AssertionType::GREATER_THAN_OR_EQUAL => "expected value to be greater than or equal to [...]",
            AssertionType::LESS_THAN => "expected value to be less than [...]",
            AssertionType::LESS_THAN_OR_EQUAL => "expected value to be less than or equal to [...]",
            AssertionType::IS_NULL => "expected value to be null",
            AssertionType::IS_NOT_NULL => "expected value to not be null",
            AssertionType::EMPTY => "expected value to be empty",
            default => "Failed",
        };
    }

    private function evaluate(): bool
    {
        return $this->state = match ($this->type) {
            AssertionType::EQUALS => $this->expected === $this->actual,
            AssertionType::EQUALS_LOOSELY => $this->expected == $this->actual,
            AssertionType::GREATER_THAN => $this->expected > $this->actual,
            AssertionType::GREATER_THAN_OR_EQUAL => $this->expected >= $this->actual,
            AssertionType::LESS_THAN => $this->expected < $this->actual,
            AssertionType::LESS_THAN_OR_EQUAL => $this->expected <= $this->actual,
            AssertionType::IS_NULL => is_null($this->expected),
            AssertionType::IS_NOT_NULL => !is_null($this->expected),
            AssertionType::EMPTY => empty($this->expected),
            default => false,
        };
    }

    private function solidify(mixed $actual, AssertionType $type): static
    {
        $this->actual = $actual;
        $this->type = $type;
        $this->test->passFail($this->evaluate());
        $this->test->getAssertions()->add($this->test->getName(), $this);
        return $this;
    }
}
