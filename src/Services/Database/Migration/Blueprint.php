<?php

namespace Vyui\Services\Database\Migration;

use Closure;

class Blueprint
{
    protected string $table;

    protected array $columns = [];

    public function setTable(string $table): static
    {
        $this->table = $table;

        return $this;
    }

    public function fields(Closure $callback): static
    {
        $callback($this);

        return $this;
    }

    public function string(string $column, int $length = 255): ColumnDefinition
    {
        return (new ColumnDefinition($column, 'string'))->length($length);
    }
}
