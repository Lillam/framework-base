<?php

namespace Vyui\Support\Facades;

use Vyui\Services\Facades\Facade;
use Vyui\Services\Filesystem\Filesystem;
use Vyui\Contracts\Filesystem\Filesystem as FileSystemContract;

/**
 * @method static Filesystem get(string $file): string
 * @method static Filesystem files(string $files): SplFileObject[]
 * @see Filesystem
 */
class File extends Facade
{
    /**
     * Get the name of the registered abstraction.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return FilesystemContract::class;
    }
}
