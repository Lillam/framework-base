<?php

namespace Vyui\Foundation\Console\Commands;

class Migrate extends Command
{
    public function loadMigrations(): self
    {
        return $this;
    }

    public function runMigrations(): int
    {
        return 0;
    }

    public function execute(): int
    {
        return $this->loadMigrations()->runMigrations();
    }
}