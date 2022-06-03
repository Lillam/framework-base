<?php

namespace Vyui\Services\View;

use Vyui\Services\Service;
use Vyui\Services\View\ViewEngines\Vyui\VyuiEngine;
use Vyui\Services\View\ViewEngines\Basic\BasicEngine;
use Vyui\Services\View\ViewEngines\Blade\BladeEngine;

class ViewService extends Service
{
    public function register(): void
    {
        $this->application->instance(ViewManager::class, (new ViewManager)
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