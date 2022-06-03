<?php

namespace Vyui\Services\Filesystem;

class Line
{
	/**
	 * The contents of the line of a file.
	 *
	 * @var string
	 */
    protected string $content;

	/**
	 * @param string $content
	 */
    public function __construct(string $content)
    {
        $this->content = trim($content, "\n");
    }

    /**
     * Transform the line into a string and return it to the necessary place of access as a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->content;
    }
}