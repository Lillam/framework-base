<?php

namespace Vyui\Services\Logger;

use DateTime;
use Vyui\Foundation\Application;
use Vyui\Support\Helpers\_String;
use Vyui\Services\Filesystem\FilesystemContract as Filesystem;

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
    protected string $path;

    /**
     * @param Application $application
     * @param Filesystem $filesystem
     */
    public function __construct(Application $application, Filesystem $filesystem, string $path = '/storage/framework/logs/vyui.log')
    {
        $this->application = $application;
        $this->filesystem = $filesystem;
        $this->path = $path;
    }

    /**
     * Log something to a file via a delay to the log output.
     *
     * @param string $contents
     * @return static
     */
    public function log(string $contents): static
    {
        $this->output .= "$contents \n";

        return $this;
    }

    /**
     * Log something to a file, immediately.
     *
     * @param string $contents
     * @return void
     */
    public function directLog(string $contents): void
    {
        $this->filesystem->writeLine($this->getLogFilePath(), $this->getLogFileContents("$contents \n"));
    }

    /**
     * Get the current timestamp at the point of activating the log.
     *
     * @return string
     */
    private function getCurrentTimeStamp(): string
    {
        return (new DateTime)->format('Y-m-d H:i');
    }

    /**
     * Get the file log path.
     *
     * @return string
     */
    private function getLogFilePath(): string
    {
        return $this->application->getBasePath($this->path);
    }

    /**
     * Get the log file contents, whether that's from the output passed through or from the contents that has been
     * attached to the object.
     *
     * @param string|null $output
     * @return string
     */
    private function getLogFileContents(?string $output = null): string
    {
        return _String::fromString($output ?? $this->output)->prepend($this->getCurrentTimeStamp())
                                                            ->toString();
    }

    /**
     * When this objects life span has been completed all the contents of the output will be dumped into a file
     * this will stop the unnecessary continuous writes to the file, reading/writing times.
     *
     * @return void
     */
    public function __destruct()
    {
        $this->filesystem->writeLine($this->getLogFilePath(), $this->getLogFileContents());
    }
}
