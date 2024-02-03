<?php

namespace Vyui\Debug;

use Closure;

class Benchmarker
{
    /**
     * @var array
     */
    public array $tests = [];

    public function run(string $name, Closure $f): static
    {
        $start = microtime(true);

        $f();

        $end = microtime(true);

        $this->tests[$name] = $end - $start;

        return $this;
    }

    public function getFastest()
    {
        return min($this->tests);
    }

    public function getSlowest()
    {
        return max($this->tests);
    }

    /**
     * Get the tests that had been compared between two different methods.
     *
     * @return array
     */
    public function getTests(): array
    {
        return $this->tests;
    }
}
