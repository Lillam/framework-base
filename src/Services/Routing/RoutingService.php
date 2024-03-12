<?php

namespace Vyui\Services\Routing;

use Vyui\Services\Service;

class RoutingService extends Service
{
    /**
     * Register the service into the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerRouter();
        $this->registerRoutes();
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

    /**
     * Register the router into the application.
     *
     * @return void
     */
    private function registerRouter(): void
    {
        $this->application->singleton(Router::class, Router::class);
    }

    /**
     * Upon the router being registered we can then begin registering the routes in the application.
     *
     * @return void
     */
    private function registerRoutes(): void
    {
        // if there is no directory for the routes, we are going to want to make the directory; this directory
        // is pertinent in order for adding routes into the application for a view to be bound to a route.
        if (!is_dir($directory = $this->application->getBasePath("/routes"))) {
            mkdir($directory);
        }

        $files = array_diff(scandir($directory), [".", ".."]);

        foreach ($files as $fileKey => $file) {
            $fileName = str_replace(".php", "", $file);

            require_once "$directory/$file";
        }
    }
}
