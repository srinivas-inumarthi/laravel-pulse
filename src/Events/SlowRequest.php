<?php

namespace Goapptiv\Pulse\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
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

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
