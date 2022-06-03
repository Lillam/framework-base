<?php

namespace Vyui\Contracts\Database;

use Vyui\Services\Database\Connection;

interface ConnectionManagerInterface
{
	/**
	 * @param string $connection
	 * @return Connection
	 */
	public function connection(string $connection): Connection;
}