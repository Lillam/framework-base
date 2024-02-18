<?php

namespace Vyui\Foundation\Console;

use Vyui\Foundation\Application;
use Vyui\Contracts\Console\Kernel as KernelContract;
use Vyui\Foundation\Console\Commands\{File, Help, Make, Migrate, Route, Test, Format, Command};

class Kernel implements KernelContract
{
    /**
     * The application implementation.
     *
     * @var Application
     */
    protected Application $application;

    /**
     * The commands that are currently supported by the application
     *
     * @var string[]
     */
    protected array $commands = [
        'help'    => Help::class,
        'make'    => Make::class,
        'test'    => Test::class,
        'format'  => Format::class,
        'file'    => File::class,
        'migrate' => Migrate::class,
        'routes'  => Route::class
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

        return $this->application->make($this->commands[$key], [
            'arguments' => $arguments
        ]);
    }

    /**
     * Return the keys of the commands that can currently be run in the console.
     *
     * @return string[]
     */
    public function getCommands(): array
    {
        return array_keys($this->commands);
    }

    /**
     * Get the name of the command, if the developer had provided an extra command operation such like
     * routes:list; then we are going to want only the first part of this; the routes part.
     *
     * @param Input $input
     * @return string
     */
    public function getCommandName(Input $input): string
    {
        return $input->getTokens()[0];
    }

    public function getCommandTokens(Input $input): array
    {
        return array_filter($input->getTokens(), fn ($key) => $key > 0, ARRAY_FILTER_USE_KEY);
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
        $command = $this->getCommand($this->getCommandName($input), $this->getCommandTokens($input))
                        ->setOutput($output);

        return $command->execute();
    }
}
