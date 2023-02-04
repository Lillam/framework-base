<?php

namespace Vyui\Services\Database\Query\Builds;

use Exception;

trait Insert
{
    /**
     * The insert segments of the query builder.
     *
     * @var array
     */
    protected array $inserts = [];

    /**
     * @param array $inserts
     * @return $this
     * @throws Exception
     */
    public function insert(array $inserts = []): static
    {
        foreach ($inserts as $insert => $value) {
            $this->setInsert($insert)
                 ->withBinding('insert', $value);
        }

        return $this->setQueryType('insert');
    }

    /**
     * @param string $insert
     * @return $this
     */
    public function setInsert(string $insert): static
    {
        $this->inserts[] = "$insert = ?";

        return $this;
    }
}