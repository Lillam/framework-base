<?php

namespace Vyui\Services\Database\Query;

trait BuilderCompiler
{
	/**
	 * Compile the builder query so that we can utilise a finalised form to pass to the PDO.
	 *
	 * @return $this
	 */
	private function compile(): static
	{
		return $this->compileSelect()
				    ->compileJoin()
			 	    ->compileInsert()
			 	    ->compileUpdate()
			 	    ->compileDelete()
			 		->compilewheres()
			 		->compileLimit()
			 		->compileOffset()
			 		->compileFinalisedQuery();
	}

	private function compileJoin(): static
	{
		foreach ($this->joins as $join) {
			$this->query .= "$join";
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	private function compileLimit(): static
	{
		if (empty($this->limit)) {
			return $this;
		}

		$this->query .= "LIMIT $this->limit ";

		return $this;
	}

	/**
	 * @return $this
	 */
	private function compileOffset(): static
	{
		if (empty($this->limit) || empty($this->offset)) {
			return $this;
		}

		$this->query .= "OFFSET $this->offset ";

		return $this;
	}

	/**
	 * @return $this
	 */
	private function compileSelect(): static
	{
		$this->query .= 'SELECT ';

		$selects = '*';

		if ($this->selects) {
			$selects = "$this->table." . implode(", {$this->table}.", $this->selects);
		}

		$this->query .= $selects;

		$this->query .= " FROM {$this->connection->getDatabase()}.$this->table ";

		return $this;
	}

	/**
	 * @return $this
	 */
	private function compileInsert(): static
	{
		if ($this->isNotQueryType('insert')) {
			return $this;
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	private function compileUpdate(): static
	{
		if ($this->isNotQueryType('update')) {
			return $this;
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	private function compileDelete(): static
	{
		if ($this->isNotQueryType('delete')) {
			return $this;
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	private function compileWheres(): static
	{
		if (! $this->wheres) {
			return $this;
		}

		$this->query .= 'WHERE ';

		foreach ($this->wheres as $where) {
			$this->query .= "$where ";
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	private function compileFinalisedQuery(): static
	{
		$this->query = trim($this->query) . ';';

		return $this;
	}
}