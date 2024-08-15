<?php

namespace Vyui\Services\View;

use Vyui\Services\Service;
use Vyui\Services\View\ViewEngines\Vyui\Engine as VyuiEngine;
use Vyui\Services\Filesystem\FilesystemContract as Filesystem;
use Vyui\Services\View\ViewEngines\Basic\Engine as BasicEngine;

class ViewService extends Service
{
    // @todo -> add a view precedence system so that the system will know what view to search
    //          for first as it stands, the precedence is in the means in which they are
    //          registered.

    public function register(): void
    {
        // todo - right now this is tightly coupled with the file system of the application; meaning this can't be self
        //        sufficiently sustained without the need of the whole application; potentially offer alternatives based
        //        on what the application has available ready to hand.
        $viewManager = (new ViewManager($this->application->make(Filesystem::class)))
            // set the path of which the system knows where to begin looking for the files that it needs. all files will
            // be rendered from these location(s)
            ->registerPath($this->application->getPath("views"))
            ->registerStoragePath($this->application->getPath("storage.views"), true)
            // Register the following engines, these particular engines is what the system supports as it currently
            // stands...
            ->registerEngine('vyui.php', new VyuiEngine)
            ->registerEngine('basic.php', new BasicEngine);

        $this->application->instance(ViewManager::class, $viewManager);
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
