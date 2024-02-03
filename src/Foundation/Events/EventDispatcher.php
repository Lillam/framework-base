<?php

namespace Vyui\Foundation\Events;

use Closure;

class EventDispatcher {
    /**
    * The events that the dispatcher will be accessing.
    *
    * @param array<string, Closure> $events
    */
    protected array $subscribers = [];

    public function subscribe(string $event, Closure $handler): void
    {
        $this->subscribers[$event][] = $handler;
    }

    public function dispatch($event): void
    {
        if (! $this->subscribers[$event->getName()]) {
            return;
        }

        foreach ($this->subscribers[$event->getName()] as $handler) {
            $handler($event->getData());
        }
    }
}
