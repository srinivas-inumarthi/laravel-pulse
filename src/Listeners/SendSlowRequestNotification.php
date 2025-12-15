<?php

namespace Goapptiv\Pulse\Listeners;

use Goapptiv\Pulse\Utils;
use Goapptiv\Pulse\Constants;
use Illuminate\Support\Facades\Log;
use Goapptiv\Pulse\Events\SlowRequest;
use GoApptiv\Communication\EmailCommunication;
use GoApptiv\Communication\Models\Email\Email;
use Goapptiv\Pulse\Repositories\PulseEntryRepositoryInterface;
use Goapptiv\Pulse\Repositories\PulseEventCommunicationRepositoryInterface;

class SendSlowRequestNotification
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
        if(empty($comminication)) {
            Log::info("No communication found for slow request notification");
            return;
        }

        Log::info("Preparing to send slow request email notification data");
        $emailData = new Email(
            env('PULSE_FROM_EMAIL'),
            env('PULSE_TO_EMAIL'),
            $eventComminication->email_template,
            collect(
                Utils::mapVariables(json_decode($eventComminication->email_variables, true), $this->getVariables($eventComminication))
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
    private function getVariables($eventComminication)
    {
        $interval = (int) env('PULSE_CRON_INTERVAL', 10);

        return [
            "FROM_TIME" => now()->subMinutes($interval)->getTimestamp() ,
            "TO_TIME" => now()->getTimestamp(),
            "ENDPOINT" => json_decode($eventComminication->key, true)[1],
            "METHOD" => json_decode($eventComminication->key, true)[0],
            "COUNT" => $event->count,
            "THRESHOLD_MS" => env('SLOW_REQUEST_THRESHOLD', 4),
            "DASHBOARD_URL" => env('PULSE_DASHBOARD_URL')
        ];
    }
}
