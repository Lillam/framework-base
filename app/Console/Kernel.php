<?php

namespace App\Console;

use Vyui\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    public function onBoot(): void
    {
        $this->addCommand('dict', \App\Console\Commands\Dict::class);
    }
}
