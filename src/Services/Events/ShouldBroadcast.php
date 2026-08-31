<?php

namespace Vyui\Services\Events;

interface ShouldBroadcast
{
    public function channel(): string;
}
