<?php

namespace Vyui\Services\Database\Drivers\Sqlite;

use PDO;
use Vyui\Services\Database\Connection;
use Vyui\Services\Database\Drivers\Sqlite\Query\SqliteBuilder;

class SqliteConnection extends Connection
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

    public function query(): SqliteBuilder
    {
        return new SqliteBuilder($this);
    }
}