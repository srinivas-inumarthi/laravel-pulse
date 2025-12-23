<?php

namespace Goapptiv\Pulse\Listeners;

use Goapptiv\Pulse\Utils;
use Illuminate\Support\Arr;
use Goapptiv\Pulse\Constants;
use Illuminate\Support\Facades\Log;
use Goapptiv\Pulse\Events\SlowRequest;
use GoApptiv\Communication\EmailCommunication;
use GoApptiv\Communication\Models\Email\Email;
use Goapptiv\Pulse\Repositories\PulseEntryRepositoryInterface;
use Goapptiv\Pulse\Repositories\PulseEventCommunicationRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSlowRequestNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private PulseEntryRepositoryInterface $pulseEntryRepository,
        private PulseEventCommunicationRepositoryInterface $pulseEventCommunicationRepository
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

        $eventComminication = $this->pulseEventCommunicationRepository->findByEventAndStatus(SlowRequest::$SLOW_REQUEST_EVENT, Constants::$ACTIVE);
        if(empty($eventComminication)) {
            Log::info("No communication found for slow request notification");
            return;
        }

        Log::info("Preparing to send slow request email notification data");
        $emailData = new Email(
            env('PULSE_FROM_EMAIL'),
            env('PULSE_TO_EMAIL'),
            $eventComminication->email_template,
            collect(
                Utils::mapVariables(json_decode($eventComminication->email_variables, true), $this->getVariables($request, $event))
            )
        );

        Log::info("Sending slow request email notification");
        EmailCommunication::sendEmail($emailData);

        Log::info("Updating status to processed by :",['id' => $request->id]);
        $this->pulseEntryRepository->updateStatusById($request->id, Constants::$PROCESSED);
    }

    /**
     * Get variables
     *
     * @return array
     */
    private function getVariables($request, $event)
    {
        $interval = (int) env('PULSE_CRON_INTERVAL', 10);
        $keyData = json_decode($request->key, true) ?? [];
        return [
            "from_time" => now('Asia/Kolkata')->subMinutes($interval)->format('h:i A'),
            "to_time"   => now('Asia/Kolkata')->format('h:i A'),
            "endpoint" => url(Arr::get($keyData, 1, 'N/A')),
            "method" => Arr::get($keyData, 0, ''),
            "count" => $event->count,
            "threshold_min" => env('SLOW_REQUEST_THRESHOLD', 4),
            "dashboard_url" =>url('pulse')
        ];
    }
}