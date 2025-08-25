<?php

namespace Vyui\Foundation\Console\Commands;

use Vyui\Services\Filesystem\Filesystem;
use Vyui\Services\Database\Migration\Blueprint;

class Migrate extends Command
{
    private string $path;

    /**
     * @var array<string, callable>
     */
    private array $migrations = [];

    public function loadMigrations(): self
    {
        $this->path = $this->application->getBasePath('/database/migrations');

        $this->migrations = $this->application
             ->make(Filesystem::class)
             ->scanDirectory($this->path, false);

        return $this;
    }

    private function runMigration(string $file): void
    {
        (require_once $file)($this->application->make(Blueprint::class)); 
    }

    public function runMigrations(): int
    {
        foreach ($this->migrations as $migration) {
            $this->runMigration("$this->path/$migration");
        }

        return 0;
    }

    public function execute(): int
    {
        return $this->loadMigrations()->runMigrations();
    }
}
