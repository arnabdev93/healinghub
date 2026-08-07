<?php

namespace App\Services;

use Carbon\Carbon;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Illuminate\Support\Facades\Log;

class GoogleMeetService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();

        $credentialsPath = base_path(env('GOOGLE_SERVICE_ACCOUNT_JSON'));

        $this->client->setAuthConfig($credentialsPath);
        $this->client->setScopes([Calendar::CALENDAR]);
        $this->client->setSubject(env('GOOGLE_SERVICE_ACCOUNT_EMAIL'));
    }

    public function createMeeting($date, $time, $doctorEmail = null, $patientEmail = null)
    {
        try {
            $service = new Calendar($this->client);

            $startDateTime = Carbon::createFromFormat('Y-m-d H:i', "$date $time", 'Asia/Kolkata');
            $endDateTime   = $startDateTime->copy()->addMinutes(30);

            $attendees = [];

            if ($doctorEmail) {
                $attendees[] = ['email' => $doctorEmail];
            }

            if ($patientEmail) {
                $attendees[] = ['email' => $patientEmail];
            }

            $event = new Event([
                'summary'         => 'Doctor Appointment',
                'start'           => [
                    'dateTime' => $startDateTime->toRfc3339String(),
                    'timeZone' => 'Asia/Kolkata',
                ],
                'end'             => [
                    'dateTime' => $endDateTime->toRfc3339String(),
                    'timeZone' => 'Asia/Kolkata',
                ],
                'attendees'       => $attendees,
                'conferenceData'  => [
                    'createRequest' => [
                        'requestId'             => uniqid('meet_', true),
                        'conferenceSolutionKey' => [
                            'type' => 'hangoutsMeet'
                        ]
                    ]
                ],
                'guestsCanJoinSeparately' => true,
                'guestsCanModify'         => false,
            ]);

            $createdEvent = $service->events->insert(
                'primary',
                $event,
                ['conferenceDataVersion' => 1]
            );

            return $createdEvent->getHangoutLink();

        } catch (\Exception $e) {
            Log::error('Google Meet Service Account Error: ' . $e->getMessage());
            return null;
        }
    }
}
