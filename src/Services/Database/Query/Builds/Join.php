<?php

namespace Vyui\Services\Database\Query\Builds;

trait Join
{
    /**
     * The queries in which
     *
     * @var array
     */
    protected array $joins = [];

    /**
     * @param string $table
     * @param string $first
     * @param string $operator
     * @param string $second
     * @return $this
     */
    public function join(string $table, string $first, string $operator, string $second): static
    {
        $this->joins[] = "join $table on ($first $operator $second) ";

        return $this;
    }

    public function leftJoin(string $table, string $first, string $operator, string $second): static
    {
        $this->joins[] = "left join $table on ($first $operator $second) ";

        return $this;
    }

    public function rightJoin(string $table, string $first, string $operator, string $second): static
    {
        return $this;
    }

    // todo - create left join
    // todo - create right join
    // todo - create inner join???
    // todo - create outer join???
    // todo - create union join???
    // todo - create full join???
}