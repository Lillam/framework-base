<?php

namespace Vyui\Contracts\Filesystem;

use SplFileObject;
use Vyui\Services\Filesystem\Line;

interface Filesystem
{
    /**
     * Get the contents of a file.
     *
     * @param string $path
     * @return string
     */
    public function get(string $path): string;

    /**
     * Get the files of a directory.
     *
     * @param string $path
     * @param bool $hydrate
     * @return SplFileObject[]
     */
    public function files(string $path, bool $hydrate = false): array;

	/**
	 * Get the lines of a file.
	 *
	 * @param SplFileObject $file
	 * @return Line[]
	 */
	public function lines(SplFileObject $file): array;

    /**
     * Write the contents of a file.
     *
     * @param string $path
     * @param string $content
     * @param bool $lock
     * @return int|bool
     */
    public function put(string $path, string $content, bool $lock = false): int|bool;

    /**
     * Append contents to a file.
     *
     * @param string $path
     * @param string $content
     * @return int|bool
     */
    public function writeLine(string $path, string $content): int|bool;

	/**
	 * @param string $path
	 * @param int $mode
	 * @param bool $recursive
	 * @param bool $force
	 * @return bool
	 */
	public function makeDirectory(string $path, int $mode = 0755, bool $recursive = false, bool $force = false): bool;

	/**
	 * Delete a file.
	 *
	 * @param string $path
	 * @return bool
	 */
	public function delete(string $path): bool;

    /**
     * Determine whether a file exists or not.
     *
     * @param string $file
     * @return bool
     */
    public function exists(string $file): bool;

    /**
     * Determine whether a file is missing or not.
     *
     * @param string $file
     * @return bool
     */
    public function missing(string $file): bool;
}