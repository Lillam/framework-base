<?php

namespace Vyui\Services\Database\Query\Builds;

trait Offset
{
    /**
     * Let the query know what the offset wants to be, basically how many results we want to skip from the
     * provided query
     *
     * @var int
     */
    protected int $offset;

    /**
     * Set the
     *
     * @param int $offset
     * @return $this
     */
    public function offset(int $offset): static
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Alias of offset.
     *
     * @param int $skip
     * @return $this
     */
    public function skip(int $skip): static
    {
        return $this->offset($skip);
    }
}
