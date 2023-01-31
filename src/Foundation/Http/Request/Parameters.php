<?php

namespace Vyui\Foundation\Http\Request;

class Parameters
{
    /**
     * The Parameter Storage.
     *
     * @var array
     */
    protected array $parameters = [];

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * Get the keys from parameters of the object.
     *
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->parameters);
    }

    /**
     * Get an item identified by a key from the parameters of the object.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return array_key_exists($key, $this->parameters)
            ? $this->parameters[$key]
            : $default;
    }

    /**
     * Set an item bound to the key within the parameters of the object.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->parameters[$key] = $value;
    }

    /**
     * Remove an item that's bound by a key within the parameters of the object.
     *
     * @param string $key
     * @return void
     */
    public function remove(string $key): void
    {
        unset($this->parameters[$key]);
    }

    /**
     * Return all the parameters attached to this particular object.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->parameters;
    }
}