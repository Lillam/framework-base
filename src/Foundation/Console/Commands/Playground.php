<?php

namespace Vyui\Foundation\Console\Commands;

class Playground extends Command
{
    protected string $path {
        get => $this->application->getBasePath("/playground");
    }

    /**
     * the filenames of all the files that reside within $path in order to bolster
     * a playground that can be run from the console... this is almost akin to 
     * running a test suite but instead running a playground file.
     * 
     * @param string[] $files
     */
    protected array $files = [];

    public function execute(): int
    {
        // first things first, we want to set up the playground directory 
        // if it doesn't exist. 
        \is_dir($this->path) || \mkdir($this->path, 0755, true);

        foreach (\glob("{$this->path}/*") as $file) {
            $parts = explode('/', $file);
            $filename = end($parts);
            $this->files[\str_replace('.php', '', $filename)] = $file;
        }
        
        $playground = $this->getArgument(0);

        if (! $playground || !isset($this->files[$playground])) {
            $this->output->printError("Playground is either null or does not exist within /playgrounds");
            return 1;
        }

        // execute the playground - everything from the file will be executed
        require_once $this->files[$playground];

        return 0;
    }
}