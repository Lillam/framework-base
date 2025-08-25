<?php

namespace Vyui\Services\Database\Migration;

class Blueprint
{
    protected string $table;

    protected array $columns = [];

    public function setTable(string $table): static
    {
        $this->table = $table;

        return $this;
    }

    public function string(string $column, int $length = 255): ColumnDefinition
    {
        return (new ColumnDefinition($column, 'string'))->length($length);
    }
}
