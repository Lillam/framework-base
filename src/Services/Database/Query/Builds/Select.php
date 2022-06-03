<?php

namespace Vyui\Services\Database\Query\Builds;

use Exception;

trait Select
{
	/**
	 * The columns that to select from this particular query build instance.
	 *
	 * @var array
	 */
	protected array $selects = [];

	/**
	 * @param string ...$columns
	 * @return $this
	 * @throws Exception
	 */
	public function select(...$columns): static
	{
		$this->selects = array_merge($this->selects, $columns);

		return $this->setQueryType('select');
	}
}