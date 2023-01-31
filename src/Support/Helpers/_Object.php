<?php

namespace Vyui\Support\Helpers;

class _Object
{
    /**
     * Transform an array into an object which will give us pointer access.
     *
     * @param array $array
     * @return object
     */
    public static function arrayToObject(array $array): object
    {
        return json_decode(json_encode($array));
    }

    /**
     * Transform an object into an associative array.
     *
     * @param object $object
     * @return array
     */
    public static function objectToArray(object $object): array
    {
        return json_decode(json_encode($object), true);
    }
}