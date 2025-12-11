<?php

namespace Goapptiv\Pulse\Repositories\MySql;

use Goapptiv\Pulse\Models\PulseEntry;
use Goapptiv\Pulse\Repositories\MySql\MySqlBaseRepository;
use Goapptiv\Pulse\Repositories\PulseEntryRepositoryInterface;

class PulseEntryRepositoryImplementation extends  MySqlBaseRepository implements PulseEntryRepositoryInterface
{   
    
    /**
     * Find a pulse entry by id
     * 
     * @param int $id
     * 
     * @return PulseEntry
     */
    public function findById(int $id)
    {
        return PulseEntry::where('id', $id)->first();
    }

    /**
     * Update the status of a pulse entry by id
     * 
     * @param int $id
     * @param string $status
     * 
     * @return void
     */
    public function updateStatusById(int $id, string $status)
    {
        return PulseEntry::where('id', $id)->update(['status' => $status]);
    }
}