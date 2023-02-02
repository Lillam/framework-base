<?php

namespace Vyui\Foundation\Console\Commands;

use Vyui\Foundation\Application;
use Vyui\Contracts\Filesystem\Filesystem;

class Format extends Command
{
    /**
     * Implement the application so that we have access to the core root of the project.
     *
     * @var Application
     */
    protected Application $application;

    /**
     * Implement the file system so that we can load every single file within the application.
     *
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * All the files in the actual application.
     *
     * @var array
     */
    protected array $files = [];

    /**
     * @param Application $application
     * @param Filesystem $filesystem
     * @param array $arguments
     */
    public function __construct(Application $application, FileSystem $filesystem, array $arguments = [])
    {
        parent::__construct($arguments);
        $this->filesystem = $filesystem;
        $this->application = $application;
    }

    /**
     * @return int
     */
    public function execute(): int
    {
        $this->loadAllProjectFiles();

        return 1;
    }

    /**
     * Load all files that reside within the applications root directory; this is so that we can begin formatting all
     * the code of the project.
     *
     * @return void
     */
    private function loadAllProjectFiles(): void
    {
        $this->files = $this->filesystem->files($this->application->getBasePath(), false);

        foreach ($this->files as $file) {
            $this->output->printInfo($file);
        }

        $this->output->printSuccess(count($this->files) . ' files have been loaded');
    }
}