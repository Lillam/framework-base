<?php

namespace Vyui\Contracts\Console;

use Vyui\Foundation\Console\Input;
use Vyui\Foundation\Console\Output;
use Vyui\Foundation\Console\CommandNotFoundException;

interface Kernel
{
    /**
     * Get the commands that are available by the Console.
     *
     * @return string[]
     */
    public function getCommands(): array;

    /**
     * Take an input as well as output stream and buffer the output to the console. returning a success if the whole
     * process had been carried out successfully.
     *
     * @param Input $input
     * @param Output|null $output
     * @return int
     * @throws CommandNotFoundException
     */
    public function handle(Input $input, ?Output $output): int;
}