<?php

namespace Vyui\Services\Database;

use Exception;
use Vyui\Support\Helpers\_String;

trait Setters
{
    /**
     * Set a value within the model that's stored within the attributes.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     * @throws Exception
     */
    public function set(string $key, mixed $value): static
    {
        if (! isset($this->attributes[$key])) {
            throw new Exception("$key not found against model: " . static::class);
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Polymorphically set a value within the model that's stored within the attributes.
     *
     * @param string $method
     * @param array $arguments
     * @return $this
     * @throws Exception
     */
    public function handlePolymorphicSetter(string $method, array $arguments): static
    {
        $polymorphicKey = _String::fromString($method)->remove('set')
                                                      ->snakecase()
                                                      ->toString();

        return $this->set($polymorphicKey, $arguments[0]);
    }
}