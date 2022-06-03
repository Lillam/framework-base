<?php

namespace Vyui\Services\Filesystem;

use SplFileObject;
use Vyui\Exceptions\Filesystem\FileNotFoundException;
use Vyui\Exceptions\Filesystem\DirectoryNotFoundException;
use Vyui\Contracts\FileSystem\Filesystem as FilesystemContract;

class Filesystem implements FilesystemContract
{
    /**
     * Get the contents of a file.
     *
     * @param string $path
     * @return string
     * @throws FileNotFoundException
     */
    public function get(string $path): string
    {
        if ($this->exists($path)) {
            return file_get_contents($path);
        }

        throw new FileNotFoundException("File not found");
    }

    /**
     * Acquire all the files that might be located in a particular directory. and map them into an array of
     * SplFileObjects.
     *
     * @param string $path
     * @param bool $hydrate
     * @return SplFileObject[]
     * @throws DirectoryNotFoundException
     */
    public function files(string $path, bool $hydrate = true): array
    {
        if ($this->exists($path)) {
            return $hydrate ? array_map(function(string $file) use ($path) {
								return new SplFileObject("$path/$file");
                			}, array_diff(scandir($path), ['.', '..']))
                		    : array_diff(scandir($path), ['.', '..']);
        }

        throw new DirectoryNotFoundException("Directory not found");
    }

    /**
	 * Get the lines of a file.
	 *
     * @param SplFileObject $file
     * @return Line[]
     */
    public function lines(SplFileObject $file): array
    {
        $lines = [];

        while (! $file->eof()) {
            $lines[] = new Line($file->current());
            $file->next();
        }

        return $lines;
    }

    /**
     * Write the contents of a file.
     *
     * @param string $path
     * @param string $content
     * @param bool $lock
     * @return int|bool
     */
    public function put(string $path, string $content, bool $lock = false): int|bool
    {
        return file_put_contents($path, $content, $lock ? LOCK_EX : 0);
    }

	/**
	 * Make a directory with specific permissions.
	 *
	 * @param string $path
	 * @param int $mode
	 * @param bool $recursive
	 * @param bool $force
	 * @return bool
	 */
	public function makeDirectory(string $path, int $mode = 0755, bool $recursive = false, bool $force = false): bool
	{
		// if the directory already exists, then there's no real need to continue here. exit early and return false
		// to indicate that directory had not been made. If force has been specified then this is going to be ignored
		// and the directory will forcefully be made.
		if (! $force && $this->exists($path)) {
			return false;
		}

		return $force ? @mkdir($path, $mode, $recursive)
			          : mkdir($path, $mode, $recursive);
	}

	/**
	 * @param string $path
	 * @return bool
	 */
	public function delete(string $path): bool
	{
		return unlink($path);
	}

    /**
     * Determine whether a file exists or not.
     *
     * @param string $file
     * @return bool
     */
    public function exists(string $file): bool
    {
        return file_exists($file);
    }

    /**
     * Determine whether a file is missing or not.
     *
     * @param string $file
     * @return bool
     */
    public function missing(string $file): bool
    {
        return ! file_exists($file);
    }
}