<?php

namespace Vyui\Foundation\Console\Commands;

use Vyui\Foundation\Application;
use Vyui\Foundation\Console\Output;

abstract class Command
{
    protected Application $application;

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
     * @param Application $application
     * @param array $arguments
     */
    public function __construct(Application $application, array $arguments = [])
    {
        $this->application = $application;
        $this->arguments = $arguments;
    }

    /**
     * Execute the command
     *
     * @return int
     */
    abstract public function execute(): int;

    /**
     * @return string|null
     */
    public function getCommandArgument(): ?string
    {
        if (!str_contains($this->arguments[0], ':')) {
            return null;
        }

        return explode(':', $this->arguments[0])[1];
    }

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
