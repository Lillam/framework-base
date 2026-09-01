<?php

namespace Vyui\Foundation\Console\Commands;

use Vyui\Services\Events\Queue\{Job, Queue};

class QueueWorker extends Command
{
    private bool $quitting = false;

    public function execute(): int
    {
        // possibly able to make this the dispatcher instead and have the
        // full capabilities of the dispatcher.
        $queue = $this->application->make(Queue::class);

        $this->output->print('Waiting for jobs...');

        $this->handleSignals();

        while (!$this->quitting) {
            if (($job = $queue->pop()) === null) {
                \usleep(500_000);
                continue;
            }

            $this->process($queue, $job);
        }

        return 0;
    }

    private function process(Queue $queue, Job $job): void
    {
        $this->output->print("Processing job [{$job->id}]...");

        try {
            [$this->application->make($job->listener), 'handle']($job->toEvent());
        } catch (\Throwable $e) {
            $job->attempts++;

            $job->attempts >= 3 ? $queue->fail($job, $e)
                                : $queue->retry($job);

            // if the job has exceeded, then the message is going to be that the job
            // had failed, otherwise let the user know the job is being retried
            $message = $job->attempts >= 3 ? "failed" : "retrying";

            $this->output->print("Job [{$job->id}] attempted and {$message}");
        }

        $queue->ack($job);

        $this->output->print("Finishing processing job [{$job->id}]");
    }

    public function handleSignals(): void
    {
        if (! \function_exists('pcntl_async_signals')) {
            return;
        }

        \pcntl_async_signals(true);

        // if the user sends a SIGTERM or SIGINT (terminate, interrupt) respectively
        // then mark the worker as quitting, so that the status of the command can be
        // respected and returned to the kernel calling process.
        foreach ([SIGTERM, SIGINT] as $signal) {
            \pcntl_signal($signal, function () use ($signal) {
                $this->output->print("Received signal {$signal}, quitting...");
                $this->quitting = true;
            });
        }
    }
}
