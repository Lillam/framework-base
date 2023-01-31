<?php

namespace Vyui\Support\Helpers;

class _Array
{
    /**
     * Flatten a multi dimensional array into a singular dimensional array.
     *
     * @param array $data
     * @param bool $associatively
     * @return array
     */
    public static function flatten(array $data, bool $associatively = false): array
    {
        return (new Arrayable($data))->flatten($associatively)
                                     ->toArray();
    }

    /**
     * Take an array of keys from a top level array and return all the values that come from the array.
     *
     * @param array $keys
     * @param array $data
     * @return array
     */
    public static function only(array $keys, array $data): array
    {
        return (new Arrayable($data))->only($keys);
    }
}