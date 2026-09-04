<?php

namespace Vyui\Services\Routing;

use Closure;
use Vyui\Foundation\Application;
use Vyui\Foundation\Http\{Request, Response};
use Vyui\Foundation\Http\Middleware\Middleware;

class Pipeline 
{
    protected Request $request;

    public function __construct(
        protected Application $application
    ) { }

    /** 
     * The middleware the request is going to be carried 
     * through in order that they're registered.
     * 
     * @var Middleware[]
     */
    protected array $pipes = [];
    
    public function send(Request $request): static
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Define the middleware | pipes that this pipeline is going to travel
     * through. 
     * 
     * @param Middleware[] $pipes;
     * @return static
     */
    public function through(array $pipes): static
    {
        $this->pipes = $pipes;

        return $this;
    }

    /**
     * Get the pipeline, reversing the pipes (middleware) so that the request 
     * is filtered through each middleware in the order in which they're
     * defined. 
     * 
     * @param Closure $destination -> where the pipe is eventually landing. 
     */
    public function then(Closure $destination): Response
    {
        $pipeline = \array_reduce(
            \array_reverse($this->pipes), 
            $this->carry(), 
            $destination
        );    

        return $pipeline($this->request);
    }

    /**
     * Create the carry, when iterating over the pipes, which are either going to be 
     * fully fledged middleware, or middleware class names that need to be resolved 
     * via the container. This will be eager loading the middleware whenever the 
     * request needs it rather than preemptively creating the middleware.
     * 
     * @return Closure
     */
    protected function carry(): Closure
    {
        return fn (Closure $stack, Middleware | string $pipe): Closure
            => fn (Request $request): Response => ! $pipe instanceof Middleware 
                ? $this->application->make($pipe)->handle($request, $stack) 
                : $pipe->handle($request, $stack);
    }
}