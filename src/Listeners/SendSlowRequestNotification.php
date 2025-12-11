<?php

namespace Goapptiv\Pulse\Listeners;

use Goapptiv\Pulse\Constants;
use Goapptiv\Pulse\Events\SlowRequest;
use Goapptiv\Pulse\Repositories\PulseEntryRepositoryInterface;
use Illuminate\Support\Facades\Log;

class SendSlowRequestNotification
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private PulseEntryRepositoryInterface $pulseEntryRepository
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SlowRequest $event): void
    {
        Log::info("Sending slow request notification by :",['id' => $event->id]);
        $request = $this->pulseEntryRepository->findById($event->id);

        Log::info("Updating status to processed by :",['id' => $request->id]);
        $this->pulseEntryRepository->updateStatusById($request->id, Constants::$PROCESSED);
    }
}
