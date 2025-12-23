<?php

namespace Goapptiv\Pulse\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FailedRequest
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public static $FAILED_REQUEST_EVENT = 'FAILED_REQUEST_EVENT';
    

    /**
     * Create a new event instance.
     */
    public function __construct(
        public int $id,
        public int $count
    ) {
        
    }
}