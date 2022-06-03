<?php

namespace Vyui\Services\Database;

use Closure;
use Vyui\Exceptions\Database\ConnectionException;
use Vyui\Contracts\Database\ConnectionManagerInterface;

class ConnectionManager implements ConnectionManagerInterface
{
	/**
	 * @var Connection[]
	 */
	protected array $connections = [];

	/**
	 * @var Closure[]
	 */
	protected array $connectionFactories = [];

	/**
	 * Add a particular type of connection to the database.
	 *
	 * @param string $name
	 * @param Closure $factory
	 * @return $this
	 */
	public function registerConnection(string $name, Closure $factory): self
	{
		$this->connectionFactories[$name] = $factory;

		return $this;
	}

	/**
	 * @param string $connection
	 * @return Connection
	 */
	public function getConnection(string $connection): Connection
	{
		return $this->connections[$connection];
	}

	/**
	 * @return Connection[]
	 */
	public function getConnections(): array
	{
		return $this->connections;
	}

	/**
	 * @param string $connection
	 * @return Closure
	 */
	public function getConnectionFactory(string $connection): Closure
	{
		return $this->connectionFactories[$connection];
	}

	/**
	 * @return Closure[]
	 */
	public function getConnectionFactories(): array
	{
		return $this->connectionFactories;
	}

	/**
	 * Does the manager have the connection?
	 *
	 * @param string $connection
	 * @return bool
	 */
	public function hasConnection(string $connection): bool
	{
		return isset($this->connections[$connection]);
	}

	/**
	 * Does the manager have a factory for the chosen connection?
	 *
	 * @param string $connection
	 * @return bool
	 */
	public function hasConnectionFactory(string $connection): bool
	{
		return isset($this->connectionFactories[$connection]);
	}

	/**
	 * @param string $connection
	 * @return Connection
	 * @throws ConnectionException
	 */
	public function connection(string $connection): Connection
	{
		// if we have already established this connection then we might as well return the same connection rather than
		// seeking to instantiate and create another.
		if ($this->hasConnection($connection)) {
			return $this->getConnection($connection);
		}

		if (! $this->hasConnectionFactory($connection)) {
			throw new ConnectionException("connection: [$connection] has not been registered");
		}

		// assign the connection inside the connections from the factory that's responsible for making the connection
		// then return this connection.
		return $this->connections[$connection] = $this->getConnectionFactory($connection)($this);
	}
}