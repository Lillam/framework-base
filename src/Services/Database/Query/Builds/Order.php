<?php

namespace Vyui\Services\Database\Query\Builds;

trait Order
{
    /**
     * The order in which the results will come back from the database.
     *
     * @var array<string, string>
     */
    protected array $orders;

    /**
     * Return the orders of the current query build instance.
     *
     * @return array
     */
    public function getOrders(): array
    {
        return $this->orders;
    }
}
