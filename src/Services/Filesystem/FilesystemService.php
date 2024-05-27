<?php

namespace Vyui\Services\Filesystem;

use Vyui\Services\Service;
use Vyui\Services\Filesystem\FilesystemContract;

class FilesystemService extends Service
{
    public function register(): void
    {
        $this->application->instance(FilesystemContract::class, new Filesystem);
    }

    public function bootstrap(): void
    {

    }
}
