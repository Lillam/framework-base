<?php

namespace Vyui\Services\Config;

class ConfigStoreValue
{
    public function __construct(
        protected mixed $value
    ) {
    }

    public function get(?string $key = null): mixed
    {
        // if we don't pass a key, then we're going to either return this value as-is
        // if it doesn't happen to be an array, otherwise wrap the array so that we can
        // chain down to the next value.
        if ($key === null) {
            return \is_array($this->value) ? new self($this->value) : $this->value;
        }

        if (\str_contains($key ?? '', '.')) {
            $keys = explode('.', $key);
            $value = $this->value;

            foreach ($keys as $k) {
                if (!\is_array($value) || !isset($value[$k])) {
                    return null;
                }
                $value = $value[$k];
            }

            return \is_array($value) ? new self($value) : $value;
        }

        // if we're dealing with a wrapped value that was an array, return the array
        // value wrapped within the config store value. Otherwise if the item is not an
        // array, return it as-is.
        if (\is_array($this->value) && isset($this->value[$key])) {
            return \is_array($this->value[$key]) ? new self($this->value[$key]) : $this->value[$key];
        }

        return $this->value;
    }

    public function toArray(): array
    {
        return $this->__toArray();
    }

    public function __toArray(): array
    {
        return \is_array($this->value) ? $this->value : [$this->value];
    }
}
