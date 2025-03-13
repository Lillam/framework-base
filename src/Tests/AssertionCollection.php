<?php

namespace Vyui\Tests;

class AssertionCollection
{
    /**
    * The assertions within the collection
    *
    * @var array<string, Assertion[]>
    */
    protected array $assertions = [];

    public function add(string $key, Assertion $assertion): void
    {
        $this->assertions[$key][] = $assertion;
    }

    /**
     * @param $string $key -> the test method's assertions.
     *
     * @return Assertion[]
     */
    public function all(string $key): array
    {
        if (is_null($key)) {
            return $this->assertions;
        }

        return $this->assertions[$key] ?? [];
    }

    public function total(): int
    {
        return count($this->assertions);
    }

    public function get(): array
    {
        return $this->assertions;
    }
}
