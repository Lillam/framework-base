<?php

namespace Vyui\Foundation\Console;

use Vyui\Foundation\Application;
use Vyui\Foundation\Console\Commands\Make;
use Vyui\Foundation\Console\Commands\Test;
use Vyui\Foundation\Console\Commands\Format;
use Vyui\Foundation\Console\Commands\Command;
use Vyui\Contracts\Console\Kernel as KernelContract;

class Kernel implements KernelContract
{
    /**
     * The application implementation.
     *
     * @var Application
     */
    protected Application $application;

    /**
     * @var string[]
     */
    protected array $commands = [
        'make'   => Make::class,
        'test'   => Test::class,
        'format' => Format::class
    ];

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Implementing the ability to add a command to the console.
     *
     * @param string $key
     * @param Command $command
     * @return $this
     * @throws CommandAlreadyExistsException
     */
    public function addCommand(string $key, Command $command): self
    {
        if (isset($this->commands[$key])) {
            throw new CommandAlreadyExistsException;
        }

        $this->commands[$key] = $command;

        return $this;
    }

    /**
     * Find and return the command, if non is set then we're going to return null.
     *
     * @param string $key
     * @param array $arguments
     * @return Command
     * @throws CommandNotFoundException
     */
    public function getCommand(string $key, array $arguments = []): Command
    {
        if (! isset($this->commands[$key])) {
            throw new CommandNotFoundException;
        }

        return $this->application->make($this->commands[$key], ['arguments' => $arguments]);
    }

    /**
     * Take an input as well as output stream and buffer the output to the console. returning a success if the whole
     * process had been carried out successfully.
     *
     * @param Input $input
     * @param Output|null $output
     * @return int
     * @throws CommandNotFoundException
     */
    public function handle(Input $input, ?Output $output = null): int
    {
        $command = $this->getCommand($input->getCommandName(), $input->getTokens())
                        ->setOutput($output);

        return $command->execute();
    }
}