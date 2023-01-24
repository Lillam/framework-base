<?php

namespace Vyui\Foundation\Console\Commands;

abstract class Command
{
	/**
     * The arguments in which will be passed with the command.
     *
	 * @var string[]
	 */
	protected array $arguments = [];

    /**
     * @var string[]
     */
    protected array $printStack = [];

    /**
     * @var string
     */
    protected string $defaultPrintColour = "\e[97m";

    /**
     * @var string
     */
    protected string $successPrintColour = "\e[92m";

    /**
     * @var string
     */
    protected string $infoPrintColour = "\e[93m";

    /**
     * @var string
     */
    protected string $errorPrintColour = "\e[91m";


    /**
	 * @param array $arguments
	 */
	public function __construct(array $arguments = [])
	{
		$this->arguments = $arguments;
	}

    /**
     * Execute the command
     *
     * @return int
     */
	abstract public function execute(): int;

    /**
     * Dump some content out to the console; Dump the attempt of print into the print stack variable
     * so that we can refer back to what was outputted in a step by step of the command.
     *
     * @param string $printing
     * @return void
     */
    public function print(string $printing): void
    {
        $this->printStack[] = $printing;

        $printColourParsing = $this->getPrintColours();

        print str_replace(
            array_keys($printColourParsing),
            array_values($printColourParsing),
            $printing
        ) . PHP_EOL;
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

        print "{$this->successPrintColour}$printing{$this->defaultPrintColour}" . PHP_EOL;
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

        print "{$this->infoPrintColour}$printing{$this->defaultPrintColour}" . PHP_EOL;
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

        print "{$this->errorPrintColour}$printing{$this->defaultPrintColour}" . PHP_EOL;
    }

    /**
     * @return array
     */
    private function getPrintColours(): array
    {
        return [
            ':end:'     => $this->defaultPrintColour,
            ':success:' => $this->successPrintColour,
            ':error:'   => $this->errorPrintColour,
            ':info:'    => $this->infoPrintColour,
        ];
    }
}