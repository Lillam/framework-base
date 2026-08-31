<?php

namespace Vyui\Services\Events\Queue\Drivers;

use Throwable;
use Vyui\Services\Events\Queue\Job;

interface QueueDriver
{
    public function push(Job $job): void;
    public function pop(): ?Job;
    public function ack(Job $job): void;
    public function retry(Job $job): void;
    public function fail(Job $job, Throwable $exception): void;
}
