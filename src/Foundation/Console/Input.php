<?php

namespace Vyui\Foundation\Console;

class Input
{
    /**
     * The arguments that have been passed to the console application.
     *
     * @var array
     */
    private array $tokens = [];

    /**
     * private variable that will be for creating an array of tokens.
     *
     * @var int
     */
    private int $currentTokenKey = 0;

    /**
     * @param array|null $argv
     */
    public function __construct(array $argv = null)
    {
        $argv = $argv ?? $_SERVER['argv'] ?? [];

        // remove the caller from the arguments, we're not going to be needing the name of the caller within the
        // parsing of the passed arguments.
        array_shift($argv);

        $this->setupTokens($argv ?? []);
    }

    /**
     * @param array $tokens
     * @return void
     */
    private function setupTokens(array $tokens): void
    {
        for ($i = 0; $i < count($tokens); $i++) {
            if (str_contains($tokens[$i], ':')) {
                $tokensTokens = explode(':', $tokens[$i]);
                for ($j = 0; $j < count($tokensTokens); $j++) {
                    $this->tokens[$this->currentTokenKey] = $tokensTokens[$j];
                    $this->currentTokenKey++;
                }
                continue;
            }
            $this->tokens[$this->currentTokenKey] = $tokens[$i];
            $this->currentTokenKey++;
        }

        print_r($this->tokens);
    }

    /**
     * @return array
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return implode(' ', $this->tokens);
    }
}