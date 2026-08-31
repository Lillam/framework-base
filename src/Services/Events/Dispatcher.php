<?php

namespace Vyui\Services\Events;

use Vyui\Services\Service;
use InvalidArgumentException;
use Vyui\Services\Events\Queue\Queue;
use Vyui\Services\Broadcasting\Broadcaster;
use Vyui\Services\Events\{ShouldQueue, ShouldBroadcast};

class Dispatcher extends Service
{
    /** @var array<string, list<callable|class-string>> */
    private array $listeners = [];

    private string $path {
        get => $this->application->getBasePath("/resources/events/*.php");
    }

    private Queue $queue;

    public function register(): void
    {
        $this->bootstrap();

        $this->application->instance(Dispatcher::class, $this);
    }

    /**
     * bootstrap the event dispatcher with events. all php files within
     * the $path will be loaded and stored into the dispatcher and handled
     * accordingly.
     */
    public function bootstrap(): void
    {
        $this->queue = $this->application->make(Queue::class);

        foreach (glob($this->path) as $file) {
            foreach (require_once $file as $event => $listeners) {
                $this->listeners[$event] = $listeners;
            }
        }
    }

    public function listen(string $event, callable|string $listener): Dispatcher
    {
        $this->listeners[$event][] = $listener;

        return $this;
    }

    public function dispatch(string|object $event, mixed $payload = null): object | string
    {
        $name    = \is_object($event) ? $event::class : $event;
        $payload = \is_object($event) ? $event : $payload;

        foreach ($this->listeners[$name] ?? [] as $listener) {
            // if any event handler returns false, stop propogation
            // and stop dispatching to any further event listener.
            if ($this->call($listener, $payload) === false) {
                break;
            }
        }

        // if the event we're dealing with has been flagged for broadcasting
        // then we'll publish it to the broadcaster.
        if ($event instanceof ShouldBroadcast) {
            $this->application->make(Broadcaster::class)->publish(
                $event->channel(),
                $event::class,
                \get_object_vars($event)
            );
        }

        return $event;
    }

    private function call(callable|string $listener, mixed $payload): mixed
    {
        // a closure or [object, method] listener is already invokable, so call
        // it directly rather than trying to resolve it out of the container.
        if (!\is_string($listener)) {
            return $listener($payload);
        }

        // if the listener is a should queue listener then we're going to go through
        // and queue the listener to be processed later; rather than executing it
        // immediately inline.
        if (\is_subclass_of($listener, ShouldQueue::class)) {
            if (!\is_object($payload)) {
                throw new InvalidArgumentException(
                    "[$listener] is queued and can only receive an object event."
                );
            }

            return $this->queue->push($listener, $payload) ?? null;
        }

        // otherwise resolve the listener class and handle it right now.
        return [$this->application->make($listener), 'handle']($payload);
    }
}
