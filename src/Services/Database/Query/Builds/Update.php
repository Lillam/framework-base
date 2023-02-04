<?php

namespace Vyui\Services\Database\Query\Builds;

use Exception;

trait Update
{
    protected array $updates = [];

    /**
     * @return $this
     * @throws Exception
     */
    public function update(): static
    {
        return $this->setQueryType('update');
    }
}