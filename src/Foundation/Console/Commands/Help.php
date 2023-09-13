<?php

namespace Vyui\Foundation\Console\Commands;

class Help extends Command
{
    public function execute(): int
    {
        $this->output->print("you have ran conjure help");
        $progress = $this->output->createProgress(100);

        for ($i = 0; $i < 100; $i++) {
            $progress->advance();
            usleep(5000);
        }

        $this->output->printSuccess("we helping?");

        return 1;
    }
}