<?php

namespace Goapptiv\Pulse\Commands;

use Goapptiv\Pulse\Constants;
use Goapptiv\Pulse\Models\PulseEntry;
use Goapptiv\Pulse\Events\FailedRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FailedRequestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:failed-requests';

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
        $failedRequests = $requests
                            ->groupBy('exception')
                            ->filter(fn ($group) => $group->count() > env('FAILED_REQUEST_THRESHOLD', 4))
                            ->map(function ($group) {
                                $record = $group->first();
                                $record->count = $group->count();
                                return $record;
                            }); 

        if($failedRequests->isEmpty()) {
            Log::info('No failed requests found');
            return;
        }
        
        foreach ($failedRequests as $request) {
            FailedRequest::dispatch($request->id,$request->count);
        }
    }
}
