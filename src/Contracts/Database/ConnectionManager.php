<?php

namespace Vyui\Contracts\Database;

use Vyui\Services\Database\Connection;

interface ConnectionManager
{
    /**
     * @param string $connection
     * @return Connection
     */
    public function connection(string $connection): Connection;
}
