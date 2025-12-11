<?php

namespace Goapptiv\Pulse\Listeners;

use Goapptiv\Pulse\Events\SlowRequest;
use Illuminate\Support\Facades\DB;

class SendSlowRequestNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SlowRequest $event): void
    {
       $request = DB::table('pulse_entries')->find($event->id);
       dd($request);
    }
}
