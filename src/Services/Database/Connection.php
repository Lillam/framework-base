<?php

namespace Vyui\Services\Database;

use PDO;

abstract class Connection
{
    abstract public function pdo(): PDO;

    abstract public function getDatabase(): string;
}