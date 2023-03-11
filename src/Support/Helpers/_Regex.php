<?php

namespace Vyui\Support\Helpers;

/**
 * Random regex rules
 * /([a-z]*).php/ a regex rule that captures: template.[something.php]
 * / {4}/ a regex rule that captures spaces (of a defined type)
 *
 */
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

    /**
     * Get a string and turn it into an acronym; example: Portable Network Graphic -> PNG
     *
     * @param string $value
     * @return string
     */
    public static function acronise(string $value): string
    {
        return implode('', array_map(function ($m) {
            return preg_replace('/[a-z]/', '', ucfirst($m))[0];
        }, explode(' ', str_replace('-', ' ', $value))));
    }
}