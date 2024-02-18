<?php

namespace Vyui\Services\View;

use Vyui\Services\Service;
use Vyui\Contracts\Filesystem\Filesystem;
use Vyui\Services\View\ViewEngines\Vyui\VyuiEngine;
use Vyui\Services\View\ViewEngines\Basic\BasicEngine;
use Vyui\Services\View\ViewEngines\Blade\BladeEngine;

class ViewService extends Service
{
    public function register(): void
    {
        // todo - right now this is tightly coupled with the file system of the application; meaning this can't be self
        //        sufficiently sustained without the need of the whole application; potentially offer alternatives based
        //        on what the application has available ready to hand.
        $viewManager = new ViewManager(
            $this->application->make(Filesystem::class)
        );

        $this->application->instance(ViewManager::class, $viewManager
            // set the path of which the system knows where to begin looking for the files that it needs. all files will
            // be rendered from these location(s)
            ->registerPath($this->application->getBasePath('/resources/views/'))
            ->registerStoragePath($this->application->getBasePath('/storage/framework/views/'), true)
            // Register the following engines, these particular engines is what the system supports as it currently
            // stands...
            ->registerEngine('basic.php', new BasicEngine)
            ->registerEngine('blade.php', new BladeEngine)
            ->registerEngine('vyui.php', new VyuiEngine)
        );
    }

    /**
     * Bootstrap the provider.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        $this->bootstrapped = true;
    }
}
