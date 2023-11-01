<?php

namespace Vyui\Foundation\Console\Commands;

use Vyui\Contracts\Filesystem\Filesystem;

class File extends Command
{
    protected Filesystem $filesystem;

    protected string $storagePath;
    protected string $content = '';

    protected int $headers = 20;
    protected int $rows = 1000000;

    protected int $bufferCount = 0;
    protected int $bufferMax = 1000;

    protected $start;

    /**
     * Prepare everything that this particular filesystem is going to need in order to execute...
     *
     * @return self
     */
    private function prepare(): self
    {
        $this->filesystem = $this->application->make(Filesystem::class);
        $this->storagePath = $this->application->getBasePath('/storage/framework/test.csv');

        $this->start = microtime(true);

        return $this;
    }

    private function runRead(): int
    {
        $file = $this->filesystem->open($this->storagePath);
        $currentCount = 0;

        while (! $file->eof()) {
            $currentCount += 1;
            $currentLine = substr($file->current(), 0, 100) . '...';
            $this->output->print("reading line: $currentCount [$currentLine]", false);
            $file->next();
        }

        return 1;
    }

    private function runWrite(): int
    {
        // create the headers of the file...
        $file = $this->filesystem->open($this->storagePath, 'w+');

        for ($h = 0; $h < $this->headers; $h++) {
            $this->content .= "Header $h";
            // add a spacer after each header just to make the content look better...
            $this->content .= ($h !== $this->headers - 1) ? ', ' : "\n";
            $this->output->print("preparing headers $h/{$this->headers}", false);
        }

        // iterate over the defined amount of rows and create a template for each. The content is going to get rather
        // large and no doubt would run out of memory if we let this run without some sense of management to memory.
        for ($r = 0; $r < $this->rows; $r++) {
            // for the amount of headers, we're going to need the same amount of columns, this will amount to
            // headers * rows - this should iterate at the current standard 20million times.
            for ($c = 0; $c < $this->headers; $c++) {
                $this->content .= "Column $c: Just checking to see how much content this file can realistically take";
                if ($c !== $this->headers - 1) {
                    $this->content .= ',';
                    continue;
                }
                $this->content .= "\n";
            }

            // increment the buffer
            $this->bufferCount += 1;

            // when we're iterating over the loops we're going to check if the buffer count has met up with the buffer
            // max and if this happens to be the case then we're going to simply reset the buffer and then print out the
            // current content to a file; this is to prevent $this->content from getting out of hand.
            if ($this->bufferCount === $this->bufferMax || $r === $this->rows - 1) {
                $this->bufferCount = 0;
                $file->fwrite($this->content);
                $this->content = '';
            }

            $this->output->print("prepared lines $r/{$this->rows}", false);
        }

        $this->filesystem->close($file);

        $this->output->print("time taken: " . microtime(true) - $this->start);

        return 1;
    }

    public function execute(): int
    {
        return $this->prepare()->runWrite();
    }
}