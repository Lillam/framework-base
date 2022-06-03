<?php

namespace Vyui\Services\Database\Query\Builds;

trait Limit
{
	/**
	 * Limit the amount of results on this particular query instance.
	 *
	 * @var int
	 */
	protected int $limit;

	/**
	 * Set the limit of entities the developer is wanting to retrieve from the database.
	 *
	 * @param int $limit
	 * @return $this
	 */
	public function limit(int $limit): static
	{
		$this->limit = $limit;

		return $this;
	}

	/**
	 * Set the limit of entities the developer is wanting to retrieve from the database.
	 * This is an alias of the limit method.
	 *
	 * @param int $take
	 * @return $this
	 */
	public function take(int $take): static
	{
		return $this->limit($take);
	}
}