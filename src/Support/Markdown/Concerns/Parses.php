<?php

namespace Vyui\Support\Markdown\Concerns;

trait Parses
{
    private function parseHeaders(): static
    {
        for ($i = 6; $i > 0; --$i) {
            $this->content = preg_replace_callback('#(\#{' . $i. '})(.*)#', function ($matches) use ($i) {
                return "<h$i>" . str_replace(' ', '', $matches[2]) . "</h$i>";
            }, $this->content);
        }

        return $this;
    }
}
