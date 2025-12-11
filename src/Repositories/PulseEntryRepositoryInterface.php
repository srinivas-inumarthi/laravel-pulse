<?php

namespace Goapptiv\Pulse\Repositories;

use Goapptiv\Pulse\Models\PulseEntry;
use Goapptiv\Pulse\Repositories\BaseRepositoryInterface;

interface PulseEntryRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find a pulse entry by id
     * 
     * @param int $id
     * 
     * @return PulseEntry
     */
    public function findById(int $id);
}