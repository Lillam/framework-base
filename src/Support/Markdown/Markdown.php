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
	 * @param string $content
	 * @return string
	 */
	public static function convert(string $content): string
	{
		return (new static($content))
			->convertHeaders()
			->__toString();
	}

	/**
	 * @param string $content
	 * @return string
	 */
	public static function parse(string $content): string
	{
		return (new static($content))
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