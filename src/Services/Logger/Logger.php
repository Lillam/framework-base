<?php

namespace Vyui\Services\Logger;

use Vyui\Foundation\Application;
use Vyui\Contracts\Filesystem\Filesystem;
use Vyui\Contracts\Logger\Logger as LoggerContract;
use Vyui\Support\Helpers\_String;

class Logger implements LoggerContract
{
    /**
     * The application that the logger is tied to.
     *
     * @var Application
     */
    protected Application $application;

    /**
     * The file system that the logger will need in order to begin dumping contents to a file.
     *
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * The output to the log that we're going to be working with.
     *
     * @var string|null
     */
    protected ?string $output = null;

    /**
     * The storage path in which the logs will be situated.
     *
     * @var string
     */
    protected string $path = "/storage/framework/logs/vyui.log";

    /**
     * @param Application $application
     * @param Filesystem $filesystem
     */
    public function __construct(Application $application, Filesystem $filesystem)
    {
        $this->application = $application;
        $this->filesystem = $filesystem;
    }

    /**
     * Log something to a file.
     *
     * @param string $contents
     * @return static
     */
    public function log(string $contents): static
    {
        $this->output .= "$contents \n";

        return $this;
    }

    public function directLog(string $contents): void
    {
        $this->filesystem->writeLine(
            $this->application->getBasePath($this->path),
            _String::fromString("$contents \n")
                ->prepend((new \DateTime)->format('Y-m-d H:i'))
        );
    }

    /**
     * When this objects life span has been completed all the contents of the output will be dumped into a file
     * this will stop the unnecessary continuous writes to the file, reading/writing times.
     */
    public function __destruct()
    {
        $this->filesystem->writeLine(
            $this->application->getBasePath($this->path),
            _String::fromString($this->output)
                ->prepend((new \DateTime)->format('Y-m-d H:i'))
        );
    }
}