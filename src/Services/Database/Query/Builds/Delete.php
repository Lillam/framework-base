<?php

namespace Vyui\Services\Database\Query\Builds;

use Exception;

trait Delete
{
    /**
     * @return $this
     * @throws Exception
     */
    public function delete(): static
    {
        return $this->setQueryType('delete');
    }
}