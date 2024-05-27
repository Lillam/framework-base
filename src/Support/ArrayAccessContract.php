<?php

namespace Vyui\Support;

interface ArrayAccessContract
{
    /**
     * @param int|string $key
     * @param mixed|null $value
     * @return void
     */
    public function set(int|string $key, mixed $value = null): void;

    /**
     * @param int|string $key
     * @return mixed
     */
    public function get(int|string $key): mixed;

    /**
     * @return array
     */
    public function all(): array;
}
