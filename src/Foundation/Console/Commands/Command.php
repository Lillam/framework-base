<?php

namespace Vyui\Foundation\Console\Commands;

abstract class Command
{
	/**
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

        print str_replace(array_keys($printColourParsing), array_values($printColourParsing), $printing) . PHP_EOL;
    }

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