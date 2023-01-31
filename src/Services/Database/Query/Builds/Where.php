<?php

namespace Vyui\Services\Database\Query\Builds;

use Closure;
use Exception;

trait Where
{
	/**
	 * The where constraints for the query instance in question.
	 *
	 * @var string[]
	 */
	protected array $wheres = [];

	/**
	 * Append a where clause to the current query.
	 *
	 * @param string|Closure $column
	 * @param string|null $operator
	 * @param mixed|null $value
	 * @return $this
	 */
	public function where(string|Closure $column, ?string $operator = null, mixed $value = null): static
	{
		if ($column instanceof Closure) {
			return $this->whereNested($column, 'AND');
		}

		return $this->setWhere($column, $operator, ! empty($this->wheres) ? 'AND' : null)
			        ->withBinding('where', $value);
	}

	/**
	 * Append an or where clause to the current query.
	 *
	 * @param string|Closure $column
	 * @param string|null $operator
	 * @param mixed|null $value
	 * @return $this
	 * @throws Exception
	 */
	public function orWhere(string|Closure $column, ?string $operator = null, mixed $value = null): static
	{
		if ($column instanceof Closure) {
			return $this->whereNested($column, 'OR');
		}

		return $this->setWhere($column, $operator, ! empty($this->wheres) ? 'OR' : null)
			        ->withBinding('where', $value);
	}

	/**
	 * Create a nested where constraint, allowing a wrapped constraint inside paranthesis... I.E
	 * where (column = something or column = something else)
	 *
	 * @param Closure $where
	 * @param string $boolean
	 * @return $this
	 */
	private function whereNested(Closure $where, string $boolean = ''): static
	{
		// Create an entirely new query instance as we'll be needing to wrap the where parameters within paranthesis
		// method of simplicity, so that it's possible to acquire all that was set against the query instance.
		$where($query = $this->newQuery());

		// decide whether this particular query instance wants to be utilising the boolean or not, and if
		// it does, we're going to pass it into the query string otherwise we can assume it's the start of the query
		// and thus will leave it out.
		$boolean = ! empty($this->wheres) ? "$boolean" : '';

		$this->wheres[] = "$boolean (" . implode(' ', $query->getWheres()) . ')';

		// the current instance of the query builder wants to take in the nested bindings of where. and thus we're
		// going to pass in the query's bindings and pass them into the current instance's bindings for a full set
		// of bindings.
		$this->withBindings('where', $query->getBindings('where'));

		return $this;
	}

	/**
	 * Set a where constraint on the particular query instance
	 *
	 * This is an internal method and shouldn't be used for adding where parameters to the query.
	 *
	 * @param string $column
	 * @param string $operator
	 * @param string|null $boolean
	 * @return $this
	 */
	private function setWhere(string $column, string $operator, ?string $boolean = null): static
	{
		// $this->isValidOperator($operator);

		$this->wheres[] = "$boolean $column $operator ?";

		return $this;
	}

	/**
	 * Get the set where constraints against the particular query instance.
	 *
	 * @return array
	 */
	public function getWheres(): array
	{
		return $this->wheres;
	}
}