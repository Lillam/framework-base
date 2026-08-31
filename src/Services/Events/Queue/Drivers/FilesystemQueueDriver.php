<?php

namespace Vyui\Services\Events\Queue\Drivers;

use Throwable;
use Vyui\Services\Events\Queue\Job;

final class FilesystemQueueDriver implements QueueDriver
{
    public function __construct(private string $path)
    {
        // iterate over the directories and create them if they don't exist
        // this will be for maintaining knowledge of what's been sent,
        // pending to be sent or has failed to send.
        foreach (['pending', 'reserved', 'failed'] as $directory) {
            \is_dir("{$this->path}/{$directory}") || \mkdir("{$this->path}/{$directory}", 0755, true);
        }
    }

    public function push(Job $job): void
    {
        \file_put_contents("{$this->path}/pending/{$job->id}.json", $job->toJson());
    }

    public function pop(): ?Job
    {
        foreach (\glob("{$this->path}/pending/*.json") as $file) {
            $reserved = "{$this->path}/reserved/" . \basename($file);

            // rename() is atomic on a local filesystem. If two workers spot the
            // same file, exactly one rename succeeds — the loser just moves on
            // to the next file. This is the only concurrency primitive needed.
            if (@\rename($file, $reserved) && $content = \file_get_contents($reserved)) {
                return Job::fromJson($content);
            }
        }

        return null;
    }

    public function ack(Job $job): void
    {
        @\unlink("{$this->path}/reserved/{$job->id}.json");
    }

    public function retry(Job $job): void
    {
        $this->push($job);

        @\unlink("{$this->path}/reserved/{$job->id}.json");
    }

    public function fail(Job $job, Throwable $e): void
    {
        \file_put_contents("{$this->path}/failed/{$job->id}.json", \json_encode(\get_object_vars($job) + [
            'failed_at' => \date(DATE_ATOM),
            'exception' => $e::class,
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], JSON_PRETTY_PRINT));

        @\unlink("{$this->path}/pending/{$job->id}.json");
    }
}
