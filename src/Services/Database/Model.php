<?php

namespace Vyui\Services\Database;

use ReflectionClass;
use Vyui\Support\_String;
use Vyui\Services\Database\Query\Builder;
use Vyui\Contracts\Database\ConnectionManagerInterface;

abstract class Model
{
	/**
	 * @var string
	 */
	protected string $table;

	/**
	 * The attributes for the model that comes back from the database.
	 *
	 * @var array
	 */
	protected array $attributes = [];

	/**
	 * The attributes that are allowed to be filled.
	 *
	 * @var array
	 */
	protected array $fillable = [];

	/**
	 * The database connection resolver for the model.
	 *
	 * @var ConnectionManagerInterface
	 */
	protected static ConnectionManagerInterface $resolver;

	/**
	 * The current set connection driver that the model will be utilising.
	 *
	 * @var string
	 */
	protected static string $connection;

	/**
	 * @param array $attributes
	 */
	public function __construct(array $attributes = [])
	{
		$this->getTable();

		$this->fillAttributes($attributes);
	}

	/**
	 * @param array $attributes
	 * @return $this
	 */
	public function fillAttributes(array $attributes): static
	{
		$this->attributes = ! empty($this->fillable)
			? array_filter($attributes, function ($key) {
				return in_array($key, $this->fillable);
			}, ARRAY_FILTER_USE_KEY)
			: $attributes;

		return $this;
	}

	/**
	 * Get the name of the table, if a name hasn't been specified then model is going to resort to utilising the name
	 * model underscore separated. i.e: snake_case.
	 *
	 * @return string
	 */
	public function getTable(): string
	{
		if (! empty($this->table)) {
			return $this->table;
		}

		return $this->table = _String::snakecase(
			(new ReflectionClass(static::class))->getShortName()
		);
	}

	/**
	 * @return Connection
	 */
	public function getConnection(): Connection
	{
		return static::resolveConnection($this->getConnectionName());
	}

	/**
	 * @param string $connection
	 * @return void
	 */
	public static function setConnection(string $connection)
	{
		static::$connection = $connection;
	}

	/**
	 * @return string
	 */
	public function getConnectionName(): string
	{
		return static::$connection;
	}

	/**
	 * @return Builder
	 */
	public static function query(): Builder
	{
		return (new static)->newBaseQuery();
	}

	/**
	 * @return array
	 */
	public static function all(): array
	{
		return (new static)->newBaseQuery()->all();
	}

	/**
	 * @param string|\Closure $column
	 * @param string|null $operator
	 * @param mixed|null $value
	 * @return Builder
	 */
	public static function where(string|\Closure $column, ?string $operator = null, mixed $value = null): Builder
	{
		return (new static)->newBaseQuery()->where($column, $operator, $value);
	}

	/**
	 * Generate a new base query for the model to be working with.
	 *
	 * @return Builder
	 */
	public function newBaseQuery(): Builder
	{
		return $this->getConnection()->query()->table(
			$this->getTable()
		)->forModel($this);
	}

	/**
	 * Get the connection from the resolver.
	 *
	 * @param string|null $connection
	 * @return Connection
	 */
	public static function resolveConnection(?string $connection = null): Connection
	{
		return static::$resolver->connection($connection);
	}

	/**
	 * Set the database connection resolver to the model.
	 *
	 * @param ConnectionManager $resolver
	 * @return void
	 */
	public static function setResolver(ConnectionManager $resolver)
	{
		static::$resolver = $resolver;
	}
}