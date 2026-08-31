<?php

namespace Vyui\Services\Events\Queue;

use Throwable;
use Vyui\Services\Config;
use InvalidArgumentException;
use Vyui\Foundation\Application;
use Vyui\Services\Events\Queue\Drivers\{QueueDriver, SynchronousQueueDriver, FilesystemQueueDriver};

class Queue
{
    private QueueDriver $driver;

    private string $path {
        get => $this->application->getBasePath('/storage/queue');
    }

    public function __construct(
        protected Application $application,
    ) {
        $this->driver = $this->resolveDriver(
            $this->application->make(Config::class)->get('queue.driver', 'filesystem')
        );
    }

    /**
     * Resolve the driver based on the given driver name. This will either be defaulted
     * to the file system or utilise the one that's specified in the configuration of the
     * user's setup.
     *
     * @param string $driver
     * @return QueueDriver
     * @throws InvalidArgumentException
     */
    private function resolveDriver(string $driver): QueueDriver
    {
        return match ($driver) {
            'filesystem' => new FilesystemQueueDriver($this->path),
            'sync'       => new SynchronousQueueDriver($this->application),
            //              at this point we could potentially return the default sycnrhonous queue
            //              driver so that the events can be handled, however it might be better for
            //              the developer to know that the driver attempted isn't supported.
            default      => throw new InvalidArgumentException("Driver [$driver] is not supported."),
        };
    }

    public function push(string $listener, object $event): void
    {
        $this->driver->push(Job::for($listener, $event));
    }

    public function pop(): ?Job
    {
        return $this->driver->pop();
    }

    public function ack(Job $job): void
    {
        $this->driver->ack($job);
    }

    public function retry(Job $job): void
    {
        $this->driver->retry($job);
    }

    public function fail(Job $job, Throwable $exception): void
    {
        $this->driver->fail($job, $exception);
    }
}
