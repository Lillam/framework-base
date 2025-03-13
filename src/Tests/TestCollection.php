<?php

namespace Vyui\Tests;

class TestCollection
{
    /**
     * The items within the collection
     *
     * @var Test[]
     */
    protected array $items = [];

    /**
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @param int|string $key
     * @param mixed $value
     * @return $this
     */
    public function add(int|string $key, mixed $value): static
    {
        $this->items[$key] = $value;

        return $this;
    }

    /**
     * @param int|string $key
     * @return $this
     */
    public function remove(int|string $key): static
    {
        unset($this->items[$key]);

        return $this;
    }

    /**
     * @param int|string $key
     * @return bool
     */
    public function exists(int|string $key): bool
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * @return Test[]
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }
}
