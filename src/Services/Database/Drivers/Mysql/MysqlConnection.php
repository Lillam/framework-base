<?php

namespace Vyui\Services\Database\Drivers\Mysql;

use PDO;
use Vyui\Services\Database\Connection;
use Vyui\Services\Database\Drivers\Mysql\Query\MysqlBuilder;

class MysqlConnection extends Connection
{
	private PDO $pdo;

	/**
	 * @var string
	 */
	protected string $host;

	/**
	 * @var string
	 */
	protected string $port;

	/**
	 * @var string
	 */
	protected string $database;

	/**
	 * @var string
	 */
	protected string $username;

	/**
	 * @var string
	 */
	protected string $password;

	/**
	 * @param string $host
	 * @param string $port
	 * @param string $database
	 * @param string $username
	 * @param string $password
	 */
	public function __construct(string $host, string $port, string $database, string $username, string $password)
	{
		$this->host = $host;
		$this->port = $port;
		$this->database = $database;
		$this->username = $username;
		$this->password = $password;

		$this->pdo = new PDO(
			"mysql:host=$host;port=$port;dbname=$database",
			$username,
			$password
		);
	}

	/**
	 * Get the name of the database.
	 *
	 * @return string
	 */
	public function getDatabase(): string
	{
		return $this->database;
	}

	public function pdo(): PDO
	{
		return $this->pdo;
	}

	public function query(): MysqlBuilder
	{
		return new MysqlBuilder($this);
	}
}