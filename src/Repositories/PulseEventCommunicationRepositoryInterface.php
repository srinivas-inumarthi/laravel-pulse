<?php

namespace Goapptiv\Pulse\Repositories;

use Goapptiv\Pulse\Models\PulseEventCommunication;

interface PulseEventCommunicationRepositoryInterface
{
    /**
     * Find a pulse event communication by event and status
     * 
     * @param string $event
     * @param string $status
     * 
     * @return PulseEventCommunication
     */
    public function findByEventAndStatus(string $event, string $status);
}
