<?php

namespace Vyui\Services\Database\Drivers\Redis;

use PDO;
use Vyui\Services\Database\Connection;

class RedisConnection extends Connection
{
	private PDO $pdo;

	protected string $path;

	public function __construct(string $path)
	{
		$this->path = $path;

		$this->pdo = new PDO("sqlite:$path");
	}

	public function getDatabase(): string
	{
		return $this->path;
	}

	public function pdo(): PDO
	{
		return $this->pdo;
	}
}