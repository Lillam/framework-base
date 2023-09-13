<?php

namespace Vyui\Services\Database;

use Closure;
use Exception;
use Vyui\Support\Helpers\_String;
use Vyui\Support\Helpers\_Reflect;
use Vyui\Services\Database\Query\Builder;
use Vyui\Contracts\Database\ConnectionManager;

abstract class Model
{
    use Getters, Setters;

    /**
     * @var string
     */
    protected string $table;

    /**
     * The identifier for this particular model.
     *
     * @var string
     */
    protected string $primaryKey = 'id';

    /**
     * The attributes for the model that comes back from the database.
     *
     * @var array
     */
    protected array $attributes = [];

    /**
     * The attributes for the model of which will be stripped from the return values.
     *
     * @var array
     */
    protected array $except = [];

    /**
     * The attributes that are allowed to be filled.
     *
     * @var array
     */
    protected array $fillable = [];

    /**
     * The database connection resolver for the model.
     *
     * @var ConnectionManager
     */
    protected static ConnectionManager $resolver;

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

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function fillAttributes(array $attributes): static
    {
        $this->attributes = $this->throughFillable($attributes);

        return $this;
    }

    /**
     * Filter out the attributes that aren't in the fillable column; if they don't exist then we don't want to be
     * bringing these particular values back with the model.
     *
     * @param $attributes
     * @return array
     */
    protected function throughFillable($attributes): array
    {
        if (! empty($this->except)) {
            return array_filter($attributes, function ($key) {
                return ! in_array($key, $this->except);
            }, ARRAY_FILTER_USE_KEY);
        }

        return $attributes;
    }

    /**
     * Get the name of the table, if a name hasn't been specified then model is going to resort to utilising the name
     * model underscore separated. i.e: snake_case.
     *
     * @return string
     * @throws
     */
    public function getTable(): string
    {
        if (! empty($this->table)) {
            return $this->table;
        }

        return $this->table = _String::snakecase(
            _Reflect::getClassShortName(static::class)
        );
    }

    /**
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
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
    public static function setConnection(string $connection): void
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
     * @param string|Closure $column
     * @param string|null $operator
     * @param mixed|null $value
     * @return Builder
     */
    public static function where(string|Closure $column, ?string $operator = null, mixed $value = null): Builder
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
        return $this->getConnection()->query()
                                     ->table($this->getTable())
                                     ->forModel($this);
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
    public static function setResolver(ConnectionManager $resolver): void
    {
        static::$resolver = $resolver;
    }

    /**
     * Create some magic methods to the model where the developer will be able to start interacting with the model in a
     * polymorphic way and allowing a dynamic range of methods for the way in which the developer might want to interact
     * with the model in question.
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call(string $method, array $arguments)
    {
        // dynamically get a property out of the attributes for the model in question, this will allow the developer to
        // be able to make magic calls to the model for acquiring some data out of the model in a method oriented way
        // i.e. getTitle() getProjectName() and more.
        if (str_starts_with($method, 'get') !== false) {
            return $this->handlePolymorphicGetter($method);
        }

        // dynamically set a property within the attributes for the model in question, this will allow the developer to
        // be able to make magic calls to the model for setting some data within the model in a method oriented way
        // i.e. setTitle('value') setProjectName('value') and more.
        if (str_starts_with($method, 'set') !== false) {
            return $this->handlePolymorphicSetter($method, $arguments);
        }

        // if we haven't specified a method to be called within the above and to be handled, then we're going to see if
        // the model itself already has a means to handle the call. if it doesn't; then this is going to result in
        // calling itself again in a recursive manner. Handle with caution.
        return $this->$method(...$arguments);
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        return $this->attributes;
    }
}