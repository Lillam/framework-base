<?php

namespace Vyui\Support\Helpers;

class Arrayable
{
    /**
     * @var array
     */
    protected array $array;

    /**
     * @param array $array
     */
    public function __construct(array $array = [])
    {
        $this->array = $array;
    }

    /**
     * Append a value to the array, whilst offering support to be able to add the particular value against a spceific
     * key.
     *
     * @param mixed $value
     * @param string|null $key
     * @return $this
     */
    public function append(mixed $value, ?string $key = null): static
    {
        $key ? $this->array[$key] = $value
             : $this->array[]     = $value;

        return $this;
    }

    /**
     * Flatten an array, multi dimensional or not into a singular dimensional array.
     *
     * @param bool $associatively
     * @return $this
     */
    public function flatten(bool $associatively = false): static
    {
        $flattener = function (array $data, ?string $iterationKey = null) use (&$flattener, $associatively) {
            foreach ($data as $key => $datum) {
                // here we are going to begin the concatenation of the key, if we already have one set then we're just
                // going to utilise that, otherwise we're going to append the new key onto the previous key so that we
                // can pass it down into the next iteration of array datums.
                $keystring = ! empty($iterationKey) ? "$iterationKey.$key"
                                                    : $key;

                // if the datum piece is an object, we're going to need to transform the object into an array so that
                // we can get all the key value pairs that resides against the object.
                if (is_object($datum)) {
                    $datum = (array) $datum;
                }

                // If the datum piece is not an array then we're completely valid to be putting this entity into the
                // singular dimensional array (flat). If the user has passed that we're flattening by keys then we're
                // going to be utilising the keychain separated by dots intead: key.key.key example to capture how
                // deep the array might have been.
                if (! is_array($datum)) {
                    $associatively ? $this->append($datum, $keystring)
                                   : $this->append($datum);

                    continue;
                }

                $flattener($datum, $keystring);

                // after having flatten the array we are good to just simply remove the original key from the array
                // performing some clean up without the need to store anything else.
                unset($this->array[$key]);
            }

            return $this;
        };

        return $flattener($this->array);
    }

    /**
     * Take an array of keys from a top level array and return all the values that come from the array.
     *
     * @param array $keys
     * @return array
     */
    public function only(array $keys): array
    {
        return array_filter($this->array, function ($key) use ($keys) {
            return in_array($key, $keys);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Get a value from the key that's stored in the array against the object.
     *
     * @param mixed $key
     * @return mixed
     */
    public function get(mixed $key): mixed
    {
        return $this->array[$key] ?? null;
    }

    /**
     * Store a key and value pair into the array that's stored against the object.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set(string $key, mixed $value): static
    {
        $this->array[$key] = $value;

        return $this;
    }

    /**
     * Return the object in the form of an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->array;
    }
}
