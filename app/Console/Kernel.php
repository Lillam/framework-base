<?php

namespace App\Console;

use App\Console\Commands\Dict;
use Vyui\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Add your own commands on boot... 
     *
     * @return void
     */
    public function onBoot(): void
    {
        $this->addCommand('dict', Dict::class);        
    }
}
