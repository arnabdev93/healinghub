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

        $this->client->setClientId(env('GOOGLE_MEET_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_MEET_CLIENT_SECRET'));
        $this->client->setRedirectUri(env('GOOGLE_MEET_REDIRECT_URI'));

        $this->client->setScopes(Calendar::CALENDAR);
        $this->client->setAccessType('offline');

        $tokenPath = storage_path('app/google-calendar-token.json');

        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $this->client->setAccessToken($accessToken);
        }
    }

    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function fetchAccessToken($code)
    {
        $accessToken = $this->client->fetchAccessTokenWithAuthCode($code);

        if (isset($accessToken['error'])) {
            // dd($accessToken);
        }

        $tokenPath = storage_path('app/google-calendar-token.json');

        if (!file_exists(storage_path('app'))) {
            mkdir(storage_path('app'), 0777, true);
        }

        // Change existing refresh_token preserve
        if (file_exists($tokenPath)) {
            $existing = json_decode(file_get_contents($tokenPath), true);
            if (!isset($accessToken['refresh_token']) && isset($existing['refresh_token'])) {
                $accessToken['refresh_token'] = $existing['refresh_token'];
            }
        }

        file_put_contents(
            $tokenPath,
            json_encode($accessToken, JSON_PRETTY_PRINT)
        );
    }

    public function createMeeting($date, $time)
    {
        try {

            if ($this->client->isAccessTokenExpired()) {

                $refreshToken = $this->client->getRefreshToken();

                if ($refreshToken) {

                    $this->client->fetchAccessTokenWithRefreshToken($refreshToken);

                    $newToken = $this->client->getAccessToken();

                    if (!isset($newToken['refresh_token'])) {
                        $newToken['refresh_token'] = $refreshToken;
                    }

                    file_put_contents(
                        storage_path('app/google-calendar-token.json'),
                        json_encode($newToken, JSON_PRETTY_PRINT)
                    );

                } else {
                    throw new \Exception('Refresh token missing');
                }
            }

            $service = new Calendar($this->client);

            $start = date('c', strtotime("$date $time"));
            $end   = date('c', strtotime("$date $time +30 minutes"));

            $event = new Event([
                'summary' => 'Doctor Appointment',
                'start'   => [
                    'dateTime' => $start,
                    'timeZone' => 'Asia/Kolkata',
                ],
                'end'     => [
                    'dateTime' => $end,
                    'timeZone' => 'Asia/Kolkata',
                ],
                'guestsCanJoinSeparately' => true,
                'guestsCanModify'         => false,
                'conferenceData'          => [
                    'createRequest' => [
                        'requestId'             => uniqid('meet_', true),
                        'conferenceSolutionKey' => [
                            'type' => 'hangoutsMeet'
                        ]
                    ]
                ]
            ]);

            $createdEvent = $service->events->insert(
                'primary',
                $event,
                ['conferenceDataVersion' => 1]
            );

            return $createdEvent->getHangoutLink();

        } catch (\Exception $e) {
            Log::error('Google Meet Error: ' . $e->getMessage());
            return null;
        }
    }
}
