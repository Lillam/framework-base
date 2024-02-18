<?php

namespace Vyui\Services\Filesystem;

use Vyui\Contracts\Filesystem\Filesystem as FilesystemContract;
use Vyui\Services\Service;

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
