<?php

namespace Vyui\Support;

class _String
{
	/**
	 * This will automatically be pushed into the lowercase format as this will or could be utilised as a form of
	 * identifier.
	 *
	 * Return a string in the format of a hyphenated slug, i.e:
	 *
	 * Before: Some Title
	 * After: some-title
	 *
	 * @param string $string
	 * @return string
	 */
	public static function slug(string $string): string
	{
		return static::lower(str_replace(' ', '-', $string));
	}

	/**
	 * Return a string in lowercase format:
	 *
	 * Before: Some Title
	 * After: some title
	 *
	 * @param string $string
	 * @return string
	 */
	public static function lower(string $string): string
	{
		return strtolower($string);
	}

	/**
	 * Check to see if a particular string contains any of the following characters.
	 *
	 * @param string $string
	 * @param ...$characters
	 * @return bool
	 */
	public static function contains(string $string, ...$characters): bool
	{
		foreach ($characters as $character) {
			if (str_contains($string, $character)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Convert a string to snake-case.
	 *
	 * @param string $string
	 * @return string
	 */
	public static function snakecase(string $string): string
	{
		return strtolower(implode(
			'_',
			preg_split('/(?=[A-Z])/', lcfirst($string))
		));
	}
}