<?php

namespace Vyui\Services\Database;

use Vyui\Services\Service;
use Vyui\Services\Database\Drivers\Mysql\MysqlConnection;
use Vyui\Services\Database\Drivers\Redis\RedisConnection;
use Vyui\Services\Database\Drivers\Sqlite\SqliteConnection;
use Vyui\Contracts\Database\ConnectionManager as ConnectionManagerContract;

class DatabaseService extends Service
{
    public function register(): void
    {
        $this->application->instance(ConnectionManagerContract::class, (new ConnectionManager)
            ->registerConnection('mysql', fn () => new MysqlConnection(
                ...config('database.connections.mysql')
            ))
            ->registerConnection('sqlite', fn () => new SqliteConnection(
                ...config('database.connections.sqlite')
            ))
            ->registerConnection('redis', fn () => new RedisConnection(
                ...config('database.connections.redis')
            ))
        );

        // set the model's connection resolver to the ConnectionManager that we've established to the application
        // as well as set the model's connection to the default connection that is against config/database.php - which
        // by default will be mysql.
        Model::setResolver($this->application->make(ConnectionManagerContract::class));
        Model::setConnection(config('database.default'));
    }

    /**
     * @return void
     */
    public function bootstrap(): void
    {
        $this->bootstrapped = true;
    }
}