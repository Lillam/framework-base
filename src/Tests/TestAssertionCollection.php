<?php

namespace Vyui\Tests;

class TestAssertionCollection
{
    /**
     * The items within the collection
     *
     * @var array
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
        if ($this->exists($key)) {
            // todo add a method here that generates a warning to the user that the particular item has already been
            //      added to the array... which could potentially spark the developer to alter the means in which they
            //      are implementing a feature with this method.
        }

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
     * @return array
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