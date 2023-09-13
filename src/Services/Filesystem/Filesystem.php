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
     * @param string $path
     * @return SplFileObject
     */
    public function open(string $path): SplFileObject
    {
        return new SplFileObject($path);
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
        // todo - find a better method of handling this as this is going to be running into the exception:
        //        too many opened files; probably forgo this particular portion and not hydrate files
        //        but instead begin opening them when the file is actually being tampered with in some way?
        //        this would simplify this method to just return $this->filesRecursively($path);
        if ($hydrate) {
            foreach ($files as $fileKey => $file) {
                $files[$fileKey] = new SplFileObject($file);
            }

            // is iterating faster than that of just an array map? - or even an array walk even? - though it does look
            // a lot nicer being one line rather than several lines of code (3)... I'll have to perform the operations
            // on either side, however SplFileObject does tend to be costly, so might need to do a lazy load of their
            // type rather than having multiple concurrent SPL File Objects.
            // $files = array_map(fn (string $file) => new SplFileObject($file), $files);
        }

        return $files;
    }

    /**
     * A method to recursively find all the files within a chosen directory; recursively; if it ends up running into
     * directories then perform the search again diving in and keeping track of the entire name space; this is delayed
     * process for acquiring file information as this will just be the immediate collection of the files before
     * modifying to SPLFileObjects.
     *
     * todo fix this particular method up and make it much nicer to look at; and much nicer to work with.
     *
     * @param string $path
     * @return string[]
     */
    public function filesRecursively(string $path): array
    {
        $return = [];

        if ($this->exists($path)) {
            $files = array_diff($this->scanDirectory($path), $this->ignoredFiles);
            foreach ($files as $file) {
                if (! str_contains($file, '.')) {
                    $return = array_merge($return, $this->filesRecursively("$path/$file"));
                    continue;
                }

                $return[] = "$path/$file";
            }
        }

        return $return;
    }

    /**
     * @param string $path
     * @return array
     */
    public function scanDirectory(string $path): array
    {
        if (! is_dir($path)) {
            return [];
        }

        return scandir($path);
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