<?php

namespace Vyui\Support\Facades;

use Vyui\Services\Facades\Facade;
use Vyui\Contracts\Filesystem\Filesystem as FileSystemContract;

/**
 * @method static disk(string $disk): Filesystem
 */
class Storage extends Facade
{
    /**
     * Get the Filesystem's Facade accessor name.
     *
     * @return string
     */
    public static function getFacadeAccessor(): string
    {
        return FileSystemContract::class;
    }
}