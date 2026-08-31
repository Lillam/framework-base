<?php

namespace Vyui\Services\Events\Queue\Drivers;

use Throwable;
use Vyui\Foundation\Application;
use Vyui\Services\Events\Queue\Job;

class SynchronousQueueDriver implements QueueDriver
{
    public function __construct(
        private Application $application
    ) { }

    /**
     * Run the listener immediately, in-process. Note that the job is still built
     * and unpacked through Job::for() and Job::toEvent() — so an event that does
     * not survive serialisation blows up locally, rather than silently in a worker.
     */
    public function push(Job $job): void
    {
        [$this->application->make($job->listener), 'handle']($job->toEvent());
    }

    public function pop(): ?Job
    {
        return null;
    }

    public function ack(Job $job): void
    {
        //
    }

    public function retry(Job $job): void
    {
        //
    }

    public function fail(Job $job, Throwable $exception): void
    {
        throw $exception;
    }
}
