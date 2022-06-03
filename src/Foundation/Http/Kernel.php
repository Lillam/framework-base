<?php

namespace Vyui\Foundation\Http;

use Exception;
use Vyui\Contracts\Http\Kernel as KernelContract;
use Vyui\Foundation\Application;
use Vyui\Services\Routing\Router;

class Kernel implements KernelContract
{
    /**
     * The global accessible application.
     *
     * @var Application
     */
    protected Application $application;

    /**
     * The router that the kernel will tap into, in order for placing the request.
     *
     * @var Router
     */
    protected Router $router;

	/**
	 * @param Application $application
	 * @param Router $router
	 */
    public function __construct(Application $application, Router $router)
    {
        $this->application = $application;
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function handle(Request $request): Response
    {
        try {
            $response = $this->sendRequestThroughRouter($request);
        }

        catch (Exception $exception) {
            throw new $exception;
        }

        return $response;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function sendRequestThroughRouter(Request $request): Response
    {
        $this->application->instance(Request::class, $request);

		return $this->router->dispatch($request);
    }
}