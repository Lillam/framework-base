<?php

namespace Vyui\Services\Filesystem;

use SplFileObject;
use Vyui\Exceptions\Filesystem\FileNotFoundException;
use Vyui\Exceptions\Filesystem\DirectoryNotFoundException;
use Vyui\Contracts\FileSystem\Filesystem as FilesystemContract;

class Filesystem implements FilesystemContract
{
    protected array $ignoredFiles = ['.', '..'];

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
        // if the directory doesn't exist; then there's no point attempting to do anything else here so we're going to
        // return a DirectoryNotFoundException so we can return early.
        if (! $this->exists($path)) {
            throw new DirectoryNotFoundException("Directory not found");
        }

        // if the directory has came back with no results, or an empty array then we're going to want to return early
        // again as there would be no point continuing to do anything else within this function.
        if (empty($files = $this->filesRecursively($path))) {
            return [];
        }

        // iterate over the files and if we've specified to hydrate then we're going to want to return these particular
        // items as file items as opposed to the name of the files. We can delay the process of hydration by simply
        // returning the names of the files instead.
        foreach ($files as $fileKey => $file) {
            $files[$fileKey] = $hydrate ? new SplFileObject($file)
                : $file;
        }

        return $files;
    }

    /**
     * A method to recursively find all the files within a chosen directory; recursively; if it ends up running into
     * directories then perform the search again diving in and keeping track of the entire name space; this is delayed
     * process for acquiring file information as this will just be the immediate collection of the files before
     * modifying to SPLFileObjects.
     *
     * @param string $path
     * @return string[]
     */
    public function filesRecursively(string $path): array
    {
        $return = [];

        // if the base directory exists;
        if ($this->exists($path)) {
            $files = array_diff(scandir($path) ?: [], $this->ignoredFiles);
            foreach ($files as $file) {
                if (! str_contains($file, '.php')) {
                    $return = array_merge($return, $this->filesRecursively("$path/$file"));
                    continue;
                }

                $return[] = "$path/$file";
            }
        }

        return $return;
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
     * Append contents to a file.
     *
     * @param string $path
     * @param string $content
     * @return int|bool
     */
    public function writeLine(string $path, string $content): int|bool
    {
        return file_put_contents($path, "$content\n", LOCK_EX | FILE_APPEND);
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