<?php

namespace Vyui\Foundation\Console\Commands;

abstract class Command
{
	/**
	 * @var array
	 */
	protected array $arguments = [];

	/**
	 * @param array $arguments
	 */
	public function __construct(array $arguments = [])
	{
		$this->arguments = $arguments;
	}

	abstract public function execute(): int;
}