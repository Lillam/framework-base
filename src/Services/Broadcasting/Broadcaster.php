<?php

namespace Vyui\Services\Broadcasting;

use Vyui\Services\Service;

class Broadcaster extends Service
{
    private string $path {
        get => $this->application->getBasePath("/storage/channels");
    }

    public function register(): void
    {
        $this->bootstrap();

        $this->application->instance(Broadcaster::class, $this);
    }

    public function bootstrap(): void
    {
        // if this directory already exists do nothing otherwise create the directory
        // it's going to be used for the broadcasting channels.
        \is_dir($this->path) || \mkdir($this->path, 0755, true);
    }

    /**
     * Publish the broadcasting channels.
     */
    public function publish(string $channel, string $event, array $data = []): void
    {
        // LOCK_EX matters - two php processes publishing at once would otherwise
        // interleave their writes and corrupt a line. Appends are cheap so the lock
        // is held for microseconds.
        @\file_put_contents(
            $this->log($channel),
            \json_encode(['event' => $event, 'data' => $data, 'at' => \time()]) . "\n",
            \FILE_APPEND | LOCK_EX
        );
    }

    /**
     * Read every message written after the given cursor. The cursor is a byte
     * offset into the log, so resuming is a single fseek() rather than a re-read
     * of everything the subscriber has already seen.
     *
     * @return iterable<int, array>
     */
    public function since(string $channel, int $cursor = 0): iterable
    {
        if (!\is_file($log = $this->log($channel))) {
            return;
        }

        // if the handle could not be opened, return early nothing
        // to read.
        if (! $handle = \fopen($log, 'r')) {
            return;
        }

        \fseek($handle, $cursor);

        while(($line = \fgets($handle)) !== false) {
            yield \ftell($handle) => \json_decode($line, true);
        }

        \fclose($handle);
    }

    private function log(string $channel): string
    {
        // never interpolate a client-supplied channel into a path unfiltered —
        // "../../.env" is a perfectly valid string until you strip it.
        $channel = \preg_replace('/[^a-z0-9._-]/i', '', $channel);

        return "{$this->path}/{$channel}.log";
    }
}
