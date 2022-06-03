<?php

namespace Vyui\Support\Collection;

use Vyui\Contracts\Support\ArrayAccess;

class Collection implements ArrayAccess
{
	/**
	 * @var array
	 */
	protected array $items = [];

	/**
	 * @param int|string $key
	 * @return mixed
	 */
	public function get(int|string $key): mixed
	{
		return $this->items[$key] ?? null;
	}

	/**
	 * @param int|string $key
	 * @param mixed|null $value
	 * @return void
	 */
	public function set(int|string $key, mixed $value = null): void
	{
		$this->items[$key] = $value;
	}
}