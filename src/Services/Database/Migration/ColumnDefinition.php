<?php

namespace Vyui\Services\Database\Migration;

class ColumnDefinition
{
    protected string $name;
    protected string $type;
    protected int $length = 255;
    protected bool $nullable = false;
    protected bool $unique = false;
    protected mixed $default = null;

    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function length(int $length): static 
    {
        $this->length = $length;

        return $this;
    }

    public function nullable(bool $nullable = true): static 
    {
        $this->nullable = $nullable;

        return $this;
    }

    public function defaultValue(mixed $default): static 
    {
        return $this;
    }
}
