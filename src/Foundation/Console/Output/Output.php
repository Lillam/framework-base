<?php

namespace Vyui\Foundation\Console\Output;

use Vyui\Foundation\Console\Progress;

class Output
{
    // this part is unnecessary???
    protected array $stack = [];

    protected Colour $colour;

    public function __construct()
    {
        $this->colour = new Colour();
    }

    public function print(string $printing, $newLine = false): void
    {
        $this->stack[] = $printing;

        $output = str_replace(
            array_keys($this->colour->getColours()),
            array_values($this->colour->getColours()),
            $printing
        );

        // put the cursor to the start of the line if the developer
        // hasn't specified that they want a new line.
        print (!$newLine ? "\r" : "") . $output . ($newLine ? "\n" : "");
    }

    public function printLn(string $printing): void
    {
        $this->print($printing, true);
    }

    public function printSuccess(string $printing): void
    {
        $this->printLn(
            $this->colour->getColour(':success:') .
            $printing .
            $this->colour->getColour(':end:')
        );
    }

    public function printInfo(string $printing): void
    {
        $this->printLn(
            $this->colour->getColour(':info:') .
            $printing .
            $this->colour->getColour(':end:')
        );
    }

    public function printError(string $printing): void
    {
        $this->printLn(
            $this->colour->getColour(':error:') .
            $printing .
            $this->colour->getColour(':end:')
        );
    }

    public function printColour(string $printing, string $colour = "default"): void
    {
        $this->printLn(
            $this->colour->getColour($colour) .
            $printing .
            $this->colour->getColour(':end:')
        );
    }

    public function createProgress(int $total): Progress
    {
        return new Progress($this, $total);
    }
}
