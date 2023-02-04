<?php

namespace Vyui\Services\Database\Query;

use PDO;
use Exception;
use PDOStatement;
use Vyui\Services\Database\Model;
use Vyui\Services\Database\Connection;
use Vyui\Services\Database\Query\Builds\Join;
use Vyui\Services\Database\Query\Builds\Group;
use Vyui\Services\Database\Query\Builds\Limit;
use Vyui\Services\Database\Query\Builds\Order;
use Vyui\Services\Database\Query\Builds\Where;
use Vyui\Services\Database\Query\Builds\Insert;
use Vyui\Services\Database\Query\Builds\Offset;
use Vyui\Services\Database\Query\Builds\Select;
use Vyui\Services\Database\Query\Builds\Update;
use Vyui\Services\Database\Query\Builds\Delete;
use Vyui\Services\Database\Query\Builds\Hydration;
use Vyui\Exceptions\Database\InvalidQueryTypeException;
use Vyui\Exceptions\Database\InvalidQueryOperatorException;

abstract class Builder
{
    use BuilderCompiler;

    use Hydration, Group, Join, Limit, Offset, Order, Select, Insert, Delete, Update, Where;

    /**
     * The connection of which will be responsible for handling this particular
     * query build instance.
     *
     * @var Connection
     */
    protected Connection $connection;

    /**
     * The query build that will be getting run through the connection.
     *
     * @var string
     */
    protected string $query = '';

    /**
     * The query type that we're working with.
     *
     * @var string
     */
    protected string $type = 'select';

    /**
     * The model of which is going to be utilised for the particular query builder.
     *
     * @var Model|null
     */
    protected ?Model $model = null;

    /**
     * The table the builder is going to be executing the query from.
     *
     * @var string
     */
    protected string $table;

    /**
     * Definition of all the accepted clause operators.
     *
     * @var string[]
     */
    private array $operators = [
        '=', '<', '>', '<=', '>=', '<>', '!=', '<=>', 'like', 'like binary', 'not like', 'ilike', '&', '|', '^', '<<',
        '>>', '&~', 'rlike', 'not rlike', 'regexp', 'not regexp', '~', '~*', '!~', '!~*', 'similar to',
        'not similar to', 'not ilike', '~~*', '!~~*',
    ];

    /**
     * The types of query that's allowed against the query builder.
     *
     * @var string[]
     */
    private array $queryTypes = ['insert', 'update', 'delete', 'select'];

    /**
     * @var array
     */
    protected array $bindings = [
        'select' => [],
        'where' => [],
    ];

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Set the model of the particular query instance.
     *
     * @param Model $model
     * @return $this
     */
    public function forModel(Model $model): static
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Query with a binding and a value that will be parsed against a "?"
     *
     * @param string $binding
     * @param mixed $value
     * @return $this
     */
    private function withBinding(string $binding, mixed $value): static
    {
        $this->bindings[$binding][] = $value;

        return $this;
    }

    /**
     * Query with a binding and a set of values that will be parsed against "?"'s
     *
     * @param string $binding
     * @param array $values
     * @return $this
     */
    private function withBindings(string $binding, array $values): static
    {
        $this->bindings[$binding] = array_merge($this->getBindings($binding), $values);

        return $this;
    }

    /**
     * Set the table from where the data will be selected from.
     *
     * @param string $table
     * @return $this
     */
    public function table(string $table): static
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Alias of "table" which sets the table of where to select data from.
     *
     * @param string $table
     * @return $this
     */
    public function from(string $table): static
    {
        return $this->table($table);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        ($statement = $this->prepare())->execute($this->getFlatBindings());

        // hydrating the results into models.
        if ($this->isHydratable()) {
            return $this->hydrate($statement->fetchAll(PDO::FETCH_ASSOC));
        }

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return $this->all();
    }

    /**
     * @return string
     */
    public function getRawQuery(): string
    {
        return $this->query;
    }

    /**
     * @return Model|null
     */
    public function first(): Model|null
    {
        return $this->take(1)->all()[0] ?? null;
    }

    /**
     * @return PDOStatement
     */
    public function prepare(): PDOStatement
    {
        return $this->connection->pdo()->prepare($this->compile()->getRawQuery());
    }

    /**
     * @return $this
     */
    public function newQuery(): static
    {
        return (new static($this->connection))->table($this->table)->forModel($this->model);
    }

    /**
     * Set the current query type that is in progress.
     *
     * @param string $type
     * @return $this
     * @throws Exception
     */
    private function setQueryType(string $type): static
    {
//        if (! in_array($type, $this->queryTypes)) {
//            throw new InvalidQueryTypeException(
//                "[$type] does not exist within " . implode(',', $this->queryTypes)
//            );
//        }

        $this->type = $type;

        return $this;
    }

    /**
     * Get the type of the query that is in progress.
     *
     * @return string
     */
    private function getQueryType(): string
    {
        return $this->type;
    }

    /**
     * Check if the given query type is the one of which the developer had passed.
     *
     * @param string $type
     * @return bool
     */
    private function isQueryType(string $type): bool
    {
        return $this->getQueryType() === $type;
    }

    /**
     * Check if the given query type is not the one of which the developer had passed.
     *
     * @param string $type
     * @return bool
     */
    private function isNotQueryType(string $type): bool
    {
        return ! $this->isQueryType($type);
    }

    /**
     * Get the bindings that's against the query.
     *
     * @param string|null $binding
     * @return array
     */
    private function getBindings(string $binding = null): array
    {
        if (! $binding) {
            return $this->bindings;
        }

        return $this->bindings[$binding];
    }

    /**
     * @return array
     */
    private function getFlatBindings(): array
    {
        $bindings = [];

        foreach ($this->bindings as $bindingValues) {
            $bindings = array_merge($bindings, $bindingValues);
        }

        return array_values($bindings);
    }

    /**
     * @param string $operator
     * @return void
     * @throws Exception
     */
    private function isValidOperator(string $operator)
    {
        if (! in_array($operator, $this->operators)) {
            throw new InvalidQueryOperatorException(
                "[$operator] invalid operator, try [" . implode(',', $this->operators) . "]"
            );
        }
    }
}