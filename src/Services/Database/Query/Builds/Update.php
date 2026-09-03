<?php

namespace Vyui\Services\Database\Query\Builds;

use Exception;

trait Update
{
    protected array $updates = [];

    /**
     * @return static
     * @throws Exception
     */
    public function update(): static
    {
        return $this->setQueryType('update');
    }
}
