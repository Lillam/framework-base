<?php

namespace Vyui\Support;

class _Regex
{
    /**
     * Helper method to get everything before a particular character from a string.
     *
     * @param string $value
     * @param string $character
     * @return string
     */
    public static function allBefore(string $value, string $character): string
    {
        return preg_replace("/$character.*$/", '', $value);
    }

    /**
     * Helper method to get everything after a particular character from a string.
     *
     * @param string $value
     * @param string $character
     * @return string
     */
    public static function allAfter(string $value, string $character): string
    {
        return preg_replace("/.*$character/", '', $value);
    }
}