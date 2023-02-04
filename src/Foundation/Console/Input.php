<?php

namespace Vyui\Foundation\Console;

class Input
{
    /**
     * The arguments that have been passed to the console application.
     *
     * @var array|null
     */
    private ?array $tokens = [];

    /**
     * @param array|null $argv
     */
    public function __construct(array $argv = null)
    {
        $argv = $argv ?? $_SERVER['argv'] ?? [];

        // remove the caller from the arguments, we're not going to be needing the name of the caller within the
        // parsing of the passed arguments.
        array_shift($argv);

        $this->tokens = $argv;
    }

    /**
     * @return string|null
     */
    public function getCommandName(): ?string
    {
        return $this->tokens[0] ?? null;
    }

    /**
     * @return array
     */
    public function getTokens(): array
    {
        return array_values(
            array_filter($this->tokens, function ($token, $key) {
                return $key !== 0;
            }, ARRAY_FILTER_USE_BOTH)
        );
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return implode(' ', $this->tokens);
    }
}