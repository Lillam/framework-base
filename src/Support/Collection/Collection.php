<?php

namespace Vyui\Support\Collection;

use Vyui\Contracts\Support\ArrayAccess;

class Collection implements ArrayAccess
{
    /**
     * @var array
     */
    protected array $items = [];

    /**
     * @param array $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @param int|string $key
     * @return mixed
     */
    public function get(int|string $key): mixed
    {
        return $this->items[$key] ?? null;
    }

    /**
     * @param int|string $key
     * @param mixed|null $value
     * @return void
     */
    public function set(int|string $key, mixed $value = null): void
    {
        $this->items[$key] = $value;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Transform the object collection into an iterable array that's possible to foreach over.
     *
     * @return array
     */
    public function __toArray(): array
    {
        return $this->items;
    }
}