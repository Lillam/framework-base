<?php

namespace Vyui\Services\Events\Queue;

final class Job
{
    public function __construct(
        public string $id,
        public string $listener,
        public string $event,
        public array $payload,
        public int $attempts = 0,
    ) {
    }

    public static function for(string $listener, object $event): self
    {
        return new self(
            id: uniqid(),
            listener: $listener,
            event: $event::class,
            payload: \get_object_vars($event),
        );
    }

    public function toEvent(): object
    {
        return new $this->event(...$this->payload);
    }

    public function toJson(): string
    {
        return \json_encode(\get_object_vars($this), JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public static function fromJson(string $json): self
    {
        return new self(...\json_decode($json, true));
    }
}
