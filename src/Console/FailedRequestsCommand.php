<?php

namespace Goapptiv\Pulse\Commands;

use Goapptiv\Pulse\Constants;
use Goapptiv\Pulse\Models\PulseEntry;
use Goapptiv\Pulse\Events\SlowRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FailedRequestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:failed-requests-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Running slow request command');

        $requests = PulseEntry::forFilterByTypeAndKey(Constants::$FAILED_REQUEST,'exception')->get();
        $failedRequests = $requests->groupBy('exception')->filter(fn($group) => $group->count() > Constants::$SLOW_REQUEST_THRESHOLD)->map(fn($group) => $group->first()); 

        if($failedRequests->isEmpty()) {
            Log::info('No slow requests found');
            return;
        }
        
        foreach ($failedRequests as $request) {
            SlowRequest::dispatch($request->id);
        }
    }
}
