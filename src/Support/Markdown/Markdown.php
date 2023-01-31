<?php

namespace Vyui\Support\Markdown;

use Vyui\Support\Markdown\Concerns\Parses;
use Vyui\Support\Markdown\Concerns\Converts;

class Markdown
{
	use Converts, Parses;

	/**
	 * @var string
	 */
	private string $content;

    /**
     * @var array|string[]
     */
    private array $parses = [
        'headers',
        'paragraphs',
        'lists'
    ];

	/**
	 * @param string $content
	 */
	public function __construct(string $content)
	{
		$this->setContent($content);
	}

	/**
	 * @param string $content
	 * @return $this
	 */
	public function setContent(string $content): self
	{
		$this->content = $content;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getContent(): string
	{
		return $this->content;
	}

	/**
     * Convert and cast to string which will automatically invoke the objects __toString() method; in essence which will
     * simply be returning the object's content at the point in which will all be converted markdown.
     *
	 * @param string $content
	 * @return string
	 */
	public static function convert(string $content): string
	{
		return (string) (new self($content))->convertAll();
	}

	/**
	 * @param string $content
	 * @return string
	 */
	public static function parse(string $content): string
	{
		return (new self($content))
			->parseHeaders()
			->__toString();
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->getContent();
	}
}