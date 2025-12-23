<?php

namespace Goapptiv\Pulse\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SlowRequest
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public static $SLOW_REQUEST_EVENT = 'SLOW_REQUEST_EVENT';
    

    /**
     * Create a new event instance.
     */
    public function __construct(
        public int $id,
        public int $count
    ) {
        
    }
}
