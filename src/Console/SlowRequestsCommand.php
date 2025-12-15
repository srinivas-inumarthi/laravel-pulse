<?php

namespace Goapptiv\Pulse\Console;

use Goapptiv\Pulse\Constants;
use Goapptiv\Pulse\Models\PulseEntry;
use Goapptiv\Pulse\Events\SlowRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SlowRequestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:slow-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch slow requests';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Running slow request command');

        $requests = PulseEntry::forFilterByTypeAndKey(Constants::$SLOW_REQUEST,'route')->get();
        
        $slowRequests = $requests
                        ->groupBy('route')
                        ->filter(fn ($group) => $group->count() > env('SLOW_REQUEST_THRESHOLD', 4))
                        ->map(function ($group) {
                            $record = $group->first();
                            $record->count = $group->count();
                            return $record;
                        }); 

        if($slowRequests->isEmpty()) {
            Log::info('No slow requests found');
            return;
        }
        foreach ($slowRequests as $request) {
            SlowRequest::dispatch($request->id);
        }
    }
}
