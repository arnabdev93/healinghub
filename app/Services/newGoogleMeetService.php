<?php

namespace App\Services;

use Carbon\Carbon;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Illuminate\Support\Facades\Log;
use App\Models\DoctorGoogleToken;

class GoogleMeetService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(env('GOOGLE_MEET_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_MEET_CLIENT_SECRET'));
        $this->client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $this->client->setScopes(Calendar::CALENDAR);
        $this->client->setAccessType('offline');
    }

    public function getAuthUrl($doctorId)
    {
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
        $this->client->setState($doctorId);
        return $this->client->createAuthUrl();
    }

    public function fetchAndStoreToken($code, $doctorId)
    {
        $accessToken = $this->client->fetchAccessTokenWithAuthCode($code);

        if (isset($accessToken['error'])) {
            throw new \Exception('Google OAuth error: ' . $accessToken['error']);
        }

        DoctorGoogleToken::updateOrCreate(
            ['doctor_id' => $doctorId],
            [
                'access_token'     => json_encode($accessToken),
                'refresh_token'    => $accessToken['refresh_token'] ?? null,
                'token_expires_at' => isset($accessToken['expires_in'])
                    ? Carbon::now()->addSeconds($accessToken['expires_in'])
                    : null,
            ]
        );
    }

    public function createMeeting($date, $time, $doctorId)
    {
        try {
            $tokenRecord = DoctorGoogleToken::where('doctor_id', $doctorId)->first();

            if (!$tokenRecord) {
                throw new \Exception('Doctor Google account not connected');
            }

            $accessToken = json_decode($tokenRecord->access_token, true);
            $this->client->setAccessToken($accessToken);

            if ($this->client->isAccessTokenExpired()) {
                $refreshToken = $tokenRecord->refresh_token;

                if (!$refreshToken) {
                    throw new \Exception('Refresh token missing for doctor ' . $doctorId);
                }

                $newToken = $this->client->fetchAccessTokenWithRefreshToken($refreshToken);

                DoctorGoogleToken::where('doctor_id', $doctorId)->update([
                    'access_token'     => json_encode($this->client->getAccessToken()),
                    'token_expires_at' => Carbon::now()->addSeconds($newToken['expires_in'] ?? 3600),
                ]);
            }

            $service = new Calendar($this->client);

            $start = date('c', strtotime("$date $time"));
            $end   = date('c', strtotime("$date $time +30 minutes"));

            $event = new Event([
                'summary' => 'Doctor Appointment',
                'start' => [
                    'dateTime' => $start,
                    'timeZone' => 'Asia/Kolkata',
                ],
                'end' => [
                    'dateTime' => $end,
                    'timeZone' => 'Asia/Kolkata',
                ],
                'conferenceData' => [
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
