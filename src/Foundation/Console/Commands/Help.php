<?php

namespace Vyui\Foundation\Console\Commands;

use Vyui\Contracts\Console\Kernel;

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

        // print out the available commands that reside within the kernel's commands array...
        $this->output->print(join("\n", $this->kernel->getCommands()));

        return 1;
    }
}
