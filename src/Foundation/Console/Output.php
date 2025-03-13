<?php

namespace Vyui\Foundation\Console;

class Output
{
    protected array $printingColours = [
        'info'    => "\e[93m",
        'error'   => "\e[91m",
        "success" => "\e[92m",
        "default" => "\e[97m",

        // specific colouring options.
        "green"   => "\e[92m",
        "red"     => "\e[91m",
        "orange"  => "\e[93m",
        "cyan"    => "\e[96m",
        "purple"  => "\e[95m",
        "blue"    => "\e[94m",
        "magenta" => "\e[35m",
        "yellow"  => "\e[33m",
        "black"   => "\e[97m",
        "white"   => "\e[30m"
    ];

    /**
     * @var string[]
     */
    protected array $printStack = [];

    /**
     * Dump some content out to the console; Dump the attempt of print into the print stack variable
     * so that we can refer back to what was outputted in a step by step of the command.
     *
     * if specified that the new line is false, then the system will simply print out to the console
     * over the previous line that used to be there.
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
        ) . ($newLine ? PHP_EOL : " \r");

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

        print "{$this->getColour('success')}$printing{$this->getColour()}" . PHP_EOL;
    }

    /**
     * Dump some content out to the console; dump the attempt of print into the print stack variable
     * as the stated yellow text, upon doing so set the console output back to white.
     *
     * @param string $printing
     * @return void
     */
    public function printInfo(string $printing): void
    {
        $this->printStack[] = $printing;

        print "{$this->getColour('info')}$printing{$this->getColour()}" . PHP_EOL;
    }

    /**
     * Dump the content out to the console; dump the attempt of print into the print stack variable
     * as the stated red text, upon doing so set the console output back to white.
     *
     * @param string $printing
     * @return void
     */
    public function printError(string $printing): void
    {
        $this->printStack[] = $printing;

        print "{$this->getColour('error')}$printing{$this->getColour()}" . PHP_EOL;
    }

    /**
     * Print to the console with a specific colour in mind.
     *
     * @param string $printing -> the message you want to print to the console
     * @param string $colour   -> the colour you want to print the message in
     * @return void
     */
    public function printColour(string $printing, string $colour = "default"): void
    {
        $this->printStack[] = $printing;

        print "{$this->getColour($colour)}$printing{$this->getColour()}" . PHP_EOL;
    }

    /**
     * Get the colour from the printing colours, if the colour can't be found then display a default
     * colour in the terminal (white)
     *
     * @param $colour -> defaulted to white.
     * @return string
     */
    public function getColour(string $colour = "default"): string
    {
        return $this->printingColours[$colour] ?? $this->printingColours['default'];
    }

    /**
     * Create a progress br that will have the possibility to output to the terminal for the user
     * along with the progress that they're currently at.
     *
     * @param int $total
     * @return Progress
     */
    public function createProgress(int $total): Progress
    {
        return new Progress($this, $total);
    }

    /**
     * @return array
     */
    private function getPrintColours(): array
    {
        return [
            ':end:'     => $this->printingColours['default'],
            ':success:' => $this->printingColours['success'],
            ':error:'   => $this->printingColours['error'],
            ':info:'    => $this->printingColours['info'],
        ];
    }
}
