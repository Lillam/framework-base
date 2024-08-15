<?php

namespace Vyui\Foundation\Http;

use Throwable;
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
     */
    public function handle(Request $request): Response
    {
        // Attempt to run the request through the router.
        try {
            $response = $this->sendRequestThroughRouter($request);
        }

        // if the router cannot make the request and something has gone wrong through the pipeline then we're going to
        // error out and then render that exception as a response to the end user.
        catch (Throwable $exception) {
            $response = $this->renderException($request, $exception);
        }

        return $response;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws Throwable
     */
    public function sendRequestThroughRouter(Request $request): Response
    {
        $this->application->instance(Request::class, $request);

        return $this->router->dispatch($request);
    }

    /**
     * When something has gone wrong within the system then we're going to simply render the exception that will have
     * been hit.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return Response
     */
    private function renderException(Request $request, Throwable $exception): Response
    {
        $backtrace = debug_backtrace();

        return view('exceptions', [
            'exception' => $exception,
            'backtrace' => $backtrace
        ]);
    }
}
