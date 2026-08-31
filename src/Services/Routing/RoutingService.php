<?php

namespace Vyui\Services\Routing;

use Vyui\Services\Service;

class RoutingService extends Service
{
    protected string $path {
        get => $this->application->getBasePath("/resources/routes");
    }

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
     * Register the routes into the application upon the routing service being established.
     * all routes are defined within the $path/*.php these can be set as generic PHP files
     * where you utilise the Facade to set up routes... or alternatively can utilised
     * array syntax to define your routes if this feels better to the developer setting up
     * their routes into the application.
     *
     * The ways the developer can set up their routes into the application are:
     * /resources/routes/app.php
     * use Vyui\Support\Facades\Route;
     *
     * Route::get("/app", [Controller::class, "index"]);
     *
     * or alternatively
     *
     * return [
     *     "GET" => [
     *          "app" => [Controller::class, "index"]
     *     ]
     * ]
     *
     * @return void
     */
    private function registerRoutes(): void
    {
        \is_dir($this->path) || \mkdir($this->path, 0755, true);

        /** @var Router|null $controller */
        $router = null;

        foreach (\glob("{$this->path}/*") as $file) {
            if (($routes = require_once $file) && \is_array($routes)) {
                // Lazily load the router until we actually need it. This will only be
                // the case if the developer has specified their file as an array rather
                // than utilising the Facade.
                if (\is_null($router)) {
                    $router = $this->application->make(Router::class);
                }

                foreach ($routes as $method => $methodRoutes) {
                    foreach ($methodRoutes as $url => $handler) {
                        $router->{strtolower($method)}($url, $handler);
                    }
                }
            }
        }
    }
}
