<?php

namespace Vyui\Foundation\Console\Commands;

use Exception;
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
     * @var array
     */
    protected array $ignoredDirectories = [
        'vendor',
        'storage'
    ];

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
     * Get the ignored directories in the form of a regex string.
     *
     * @return string
     */
    private function getIgnoredProjectDirectoriesRegex(): string
    {
        $regex = implode('|', array_map(fn ($ignore) => "(\/$ignore.*)", $this->ignoredDirectories));

        return "/$regex/";
    }

    /**
     * Load all files that reside within the applications root directory; this is so that we can begin formatting all
     * the code of the project.
     *
     * @return void
     */
    private function loadAllProjectFiles(): void
    {
        $this->files = array_filter(
            $this->filesystem->files($this->application->getBasePath(), false),
            function (string $file) {
                preg_match($this->getIgnoredProjectDirectoriesRegex(), $file, $matches);
                return ! $matches && str_contains($file, '.php');
            }
        );

        foreach ($this->files as $file) {
            try {
                $this->filesystem->put(
                    $file,
                    $this->fixFile($this->filesystem->get($file))
                );

                $this->output->printSuccess("✓ Fixed up indentations for: [$file]");
            }

            catch (Exception) {
                $this->output->printSuccess("✗ Could not fix indentations for: [$file]");
            }
        }

        $this->output->printSuccess(count($this->files) . ' files have been loaded');
    }

    /**
     * @param string $fileContents
     * @return string;
     */
    private function fixFile(string $fileContents): string
    {
        // todo sort out the indentations of the files; here we're going to start writing to file in order to clean up
        //      the unnecessary spacing and wild spacing intermittently between files... at the very least this can be
        //      utilised for highlighting such issues with the project.

        return str_replace('	', '    ', $fileContents);
    }
}