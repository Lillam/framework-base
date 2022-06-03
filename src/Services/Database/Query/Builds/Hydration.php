<?php

namespace Vyui\Services\Database\Query\Builds;

use Vyui\Services\Database\Model;

trait Hydration
{
	/**
	 * Hydrate the response from the database and transform all the items into modelled entities.
	 *
	 * @return Model[]
	 */
	private function hydrate(array $items): array
	{
		return array_map(fn ($item) => new $this->model($item), $items);
	}

	/**
	 * Check to see if this particular builder instance is possible to be hydrated with the models that's assigned to
	 * this particular query builder instance.
	 *
	 * @return bool
	 */
	private function isHydratable(): bool
	{
		return $this->model instanceof Model;
	}
}