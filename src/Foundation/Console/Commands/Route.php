<?php

namespace Vyui\Foundation\Console\Commands;

use Vyui\Foundation\Application;
use Vyui\Services\Routing\Router;

class Route extends Command
{
    protected Router $router;

    /**
     * @param Application $application
     * @param Router $router
     * @param array $arguments
     */
    public function __construct(Application $application, array $arguments = [])
    {
        parent::__construct($application, $arguments);
        $this->router = $application->make(Router::class);
    }

    /**
     * @return int
     */
    public function execute(): int
    {
        foreach ($this->router->allRoutes() as $method => $routes) {
            foreach ($routes as $route) {
                $this->output->print("[$method] -> {$route->getFullUri()}");
            }
        }

        return 1;
    }
}
