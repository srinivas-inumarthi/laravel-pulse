<?php

namespace Goapptiv\Pulse\Listeners;

use Goapptiv\Pulse\Utils;
use Illuminate\Support\Arr;
use Goapptiv\Pulse\Constants;
use Illuminate\Support\Facades\Log;
use Goapptiv\Pulse\Events\FailedRequest;
use GoApptiv\Communication\EmailCommunication;
use GoApptiv\Communication\Models\Email\Email;
use Goapptiv\Pulse\Repositories\PulseEntryRepositoryInterface;
use Goapptiv\Pulse\Repositories\PulseEventCommunicationRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendFailedRequestNotification implements ShouldQueue
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
    public function handle(FailedRequest $event): void
    {
        Log::info("Sending failed request notification by :",['id' => $event->id]);
        $request = $this->pulseEntryRepository->findById($event->id);

        $eventComminication = $this->pulseEventCommunicationRepository->findByEventAndStatus(FailedRequest::$FAILED_REQUEST_EVENT, Constants::$ACTIVE);
        if(empty($eventComminication)) {
            Log::info("No communication found for failed request notification");
            return;
        }

        Log::info("Preparing to send failed request email notification data");
        $emailData = new Email(
            env('PULSE_FROM_EMAIL'),
            env('PULSE_TO_EMAIL'),
            $eventComminication->email_template,
            collect(
                Utils::mapVariables(json_decode($eventComminication->email_variables, true), $this->getVariables($request, $event))
            )
        );

        Log::info("Sending failed request email notification");
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
            "exception" => url(Arr::get($keyData, 1, 'N/A')),
            "type" => Arr::get($keyData, 0, ''),
            "count" => $event->count,
            "threshold_min" => env('FAILED_REQUEST_THRESHOLD', 4),
            "dashboard_url" =>url('pulse')
        ];
    }
}