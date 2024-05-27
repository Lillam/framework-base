<?php

namespace Vyui\Services\Database;

use Vyui\Services\Database\Connection;

interface ConnectionManagerContract
{
    /**
     * @param string $connection
     * @return Connection
     */
    public function connection(string $connection): Connection;
}
