<?php

namespace Vyui\Foundation\Console\Commands;

use Vyui\Foundation\Console\Output;

abstract class Command
{
    /**
     * The arguments in which will be passed with the command.
     *
     * @var string[]
     */
    protected array $arguments = [];

    /**
     * The output buffer for the command in particular.
     *
     * @var Output
     */
    protected Output $output;

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
     * @param Output $output
     * @return $this
     */
    public function setOutput(Output $output): static
    {
        $this->output = $output;

        return $this;
    }
}