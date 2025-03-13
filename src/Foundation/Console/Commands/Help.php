<?php

namespace Vyui\Foundation\Console\Commands;

use Vyui\Foundation\Console\KernelContract as Kernel;

class Help extends Command
{
    /**
     * The console kernel.
     *
     * @var Kernel
     */
    protected Kernel $kernel;

    /**
     * @return int
     */
    public function execute(): int
    {
        // get the current console kernel that resides in the memory of the application when instantiated.
        $this->kernel = $this->application->make(Kernel::class);

        $this->output->printColour("Welcome to conjure!", "green");
        $this->output->printColour("To get started you can run one of the following commands:", "green");
        // print out the available commands that reside within the kernel's commands array...
        // $this->output->printColour("- " . join("\n- ", $this->kernel->getCommands()), "blue");
        foreach ($this->kernel->getCommands() as $command) {
            $this->output->printColour("- " . $command . " -> help:$command", "blue");
        }

        return 1;
    }
}
