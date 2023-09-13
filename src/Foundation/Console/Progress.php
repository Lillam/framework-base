<?php

namespace Vyui\Foundation\Console;

class Progress
{
    protected Output $output;

    /**
     * @var int
     */
    protected int $total;

    /**
     * @var int
     */
    protected int $current = 0;

    /**
     * @param Output $output
     * @param int $total
     */
    public function __construct(Output $output, int $total)
    {
        $this->output = $output;
        $this->total = $total;
    }

    /**
     * Advance the progress bar within the terminal.
     *
     * @return $this
     */
    public function advance(): self
    {
        $this->current += 1;

        $repeat = str_repeat("=", $this->current);

        $colour = $this->hasFinished() ? ":success:" : ":end:";

        $this->output->print("$colour{$this->progressString()} :info:[$repeat]:end:", $this->hasFinished());

        return $this;
    }

    /**
     * Get the current progress in the format of a string so that it can be printed to the console.
     *
     * @return string
     */
    private function progressString(): string
    {
        return $this->current / $this->total * 100 . "%";
    }

    private function hasFinished(): bool
    {
        return $this->current === $this->total;
    }
}