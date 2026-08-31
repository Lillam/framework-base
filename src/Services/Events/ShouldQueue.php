<?php

namespace Vyui\Services\Events;

// an empty interface for the time being however this is simply utilised
// in order to mark a particular event/job as something that should be
// queued. Any job with ShouldQueue will be pushed through the queue
// rather than being executed synchronously.
interface ShouldQueue
{

}
