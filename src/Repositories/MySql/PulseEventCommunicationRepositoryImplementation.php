<?php

namespace Goapptiv\Pulse\Repositories\MySql;

use Goapptiv\Pulse\Models\PulseEventCommunication;
use Goapptiv\Pulse\Repositories\MySql\MySqlBaseRepository;
use Goapptiv\Pulse\Repositories\PulseEventCommunicationRepositoryInterface;

class PulseEventCommunicationRepositoryImplementation extends MySqlBaseRepository implements PulseEventCommunicationRepositoryInterface
{
    /**
     * Find a pulse event communication by event and status
     * 
     * @param string $event
     * @param string $status
     * 
     * @return PulseEventCommunication
     */
    public function findByEventAndStatus(string $event, string $status)
    {
        return PulseEventCommunication::where('event', $event)->where('status', $status)->first();
    }
}