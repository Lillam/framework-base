<?php

namespace Vyui\Foundation\Console\Commands;

use Vyui\Services\Filesystem\Filesystem;

class Migrate extends Command
{
    public function loadMigrations(): self
    {
        $files = $this->application->make(Filesystem::class)->scanDirectory(
            $this->application->getBasePath('/database/migrations'),
            false
        );

        dd($files);

        foreach ($files as $file) {
        }

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
