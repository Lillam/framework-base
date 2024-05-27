<?php

namespace Vyui\Services\Database;

use PDO;
use Vyui\Services\Database\Query\Builder;

abstract class Connection
{
    abstract public function pdo(): PDO;

    abstract public function getDatabase(): string;

    abstract public function query(): Builder;
}
