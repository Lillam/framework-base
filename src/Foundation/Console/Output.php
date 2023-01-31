<?php

namespace Vyui\Foundation\Console;

class Output
{
    /**
     * @var string
     */
    protected string $defaultOutputColour = "\e[97m";

    /**
     * @var string
     */
    protected string $successOutputColour = "\e[92m";

    /**
     * @var string
     */
    protected string $infoOutputColour = "\e[93m";

    /**
     * @var string
     */
    protected string $errorOutputColour = "\e[91m";

    /**
     * @var string[]
     */
    protected array $printStack = [];

    /**
     * Dump some content out to the console; Dump the attempt of print into the print stack variable
     * so that we can refer back to what was outputted in a step by step of the command.
     *
     * @param string $printing
     * @param bool $newLine
     * @return void
     */
    public function print(string $printing, bool $newLine = true): void
    {
        $this->printStack[] = $printing;

        $printColourParsing = $this->getPrintColours();

        $output = str_replace(
            array_keys($printColourParsing),
            array_values($printColourParsing),
            $printing
        ) . ($newLine ? PHP_EOL : '');

        print $output;
    }

    /**
     * Dump some content out to the console; dump the attempt of print into the print stack variable
     * as the stated green text, upon doing so set the console output back to being white.
     *
     * @param string $printing
     * @return void
     */
    public function printSuccess(string $printing): void
    {
        $this->printStack[] = $printing;

        print "{$this->successOutputColour}$printing{$this->defaultOutputColour}" . PHP_EOL;
    }

    /**
     * Dump some content out to the console; dump the attempt of print into the print stack varaible
     * as the stated yellow text, upon doing so set the console output back to white.
     *
     * @param string $printing
     * @return void
     */
    public function printInfo(string $printing): void
    {
        $this->printStack[] = $printing;

        print "{$this->infoOutputColour}$printing{$this->defaultOutputColour}" . PHP_EOL;
    }

    /**
     * Dump teh content out to the console; dump the attempt of print into the print stack variable
     * as the stated red text, upon doing so set the console output back to white.
     *
     * @param string $printing
     * @return void
     */
    public function printError(string $printing): void
    {
        $this->printStack[] = $printing;

        print "{$this->errorOutputColour}$printing{$this->defaultOutputColour}" . PHP_EOL;
    }

    /**
     * @return array
     */
    private function getPrintColours(): array
    {
        return [
            ':end:'     => $this->defaultOutputColour,
            ':success:' => $this->successOutputColour,
            ':error:'   => $this->errorOutputColour,
            ':info:'    => $this->infoOutputColour,
        ];
    }
}