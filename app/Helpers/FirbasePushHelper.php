<?php
namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Firebase\JWT\JWT;

class FirbasePushHelper
{
    protected $project_id;
    protected $service_accountjson;

    public function __construct()
    {
        $this->project_id = config('services.firebase.project_id');
        $this->service_accountjson = file_get_contents(
            storage_path('firebase/healinghub-service-account.json')
        );
    }

    private function getAccessToken()
    {
        $cacheFile = storage_path('firebase/token.json');
        $now = time();
        $access_token = null;

        if (file_exists($cacheFile)) {
            $cached = json_decode(file_get_contents($cacheFile), true);
            if ($cached && isset($cached['expires_at']) && $cached['expires_at'] > $now) {
                $access_token = $cached['access_token'];
            }
        }

        if (!$access_token) {
            $accessToken = $this->fetchNewAccessToken();
            if ($accessToken) {
                $access_token = $accessToken;
                file_put_contents($cacheFile, json_encode([
                    'access_token' => $accessToken,
                    'expires_at' => $now + 3500,
                ]));
            }
        }

        return $access_token;
    }

    private function fetchNewAccessToken()
    {
        $service_data = json_decode($this->service_accountjson);
        $client_email = $service_data->client_email;
        $private_key = $service_data->private_key;

        $oauthUrl = 'https://oauth2.googleapis.com/token';
        $now = time();
        $jwtPayload = [
            'iss' => $client_email,
            'sub' => $client_email,
            'aud' => $oauthUrl,
            'iat' => $now,
            'exp' => $now + 3600,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        ];

        try {
            $jwtAssertion = JWT::encode($jwtPayload, $private_key, 'RS256');
            $client = new Client();
            $response = $client->post($oauthUrl, [
                'form_params' => [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwtAssertion,
                ],
            ]);
            $authToken = json_decode($response->getBody()->getContents(), true);
            return $authToken['access_token'];
        } catch (\Exception $e) {
            \Log::info("Firebase token exception: " . $e->getMessage());
            return 0;
        }
    }

    public function sendFribasePushNotification($deviceTokens, $data, $topics = false)
    {
        $client = new Client();

        try {
            $accessToken = $this->getAccessToken();
            $fcmUrl = "https://fcm.googleapis.com/v1/projects/" . $this->project_id . "/messages:send";

            $notification_data = [
                'title' => $data['title'],
                'body' => $data['message'],
            ];

            $imageUrl = '';
            if (!empty($data['smallIcon'])) {
                $imageUrl = asset('storage/') . '/' . $data['smallIcon'];
                $notification_data['image'] = $imageUrl;
            }

            $extra_data = [];
            if (!empty($data['type'])) {
                $extra_data['type'] = $data['type'];
            }

            if ($topics == false) {
                $results = [];
                foreach ($deviceTokens as $value) {
                    $message = [
                        'message' => [
                            'token' => $value,
                            'notification' => $notification_data,
                        ],
                    ];

                    if (!empty($data['channel_id'])) {
                        $message['message']['android'] = [
                            'notification' => [
                                'sound' => $data['sound'] ?? 'default',
                                'channel_id' => $data['channel_id'],
                            ],
                        ];
                    }

                    if ($imageUrl) {
                        $message['message']['apns'] = [
                            'payload' => ['aps' => ['mutable-content' => 1]],
                            'fcm_options' => ['image' => $imageUrl],
                        ];
                        $message['message']['android'] = array_merge(
                            $message['message']['android'] ?? [],
                            ['notification' => ['image' => $imageUrl]]
                        );
                    }

                    if (!empty($extra_data)) {
                        $message['message']['data'] = $extra_data;
                    }

                    try {
                        $response = $client->post($fcmUrl, [
                            'headers' => [
                                'Authorization' => 'Bearer ' . $accessToken,
                                'Content-Type' => 'application/json',
                            ],
                            'json' => $message,
                        ]);
                        $results[] = json_decode($response->getBody()->getContents(), true);
                    } catch (ClientException $e) {
                        \Log::info("FCM push failed for token: " . $e->getMessage());
                        $results[] = "Error:" . $e->getMessage();
                    }
                }
                return $results;
            } else {
                $message = [
                    'message' => [
                        'topic' => $topics,
                        'notification' => $notification_data,
                    ],
                ];

                if ($imageUrl) {
                    $message['message']['apns'] = [
                        'payload' => ['aps' => ['mutable-content' => 1]],
                        'fcm_options' => ['image' => $imageUrl],
                    ];
                    $message['message']['android'] = [
                        'notification' => ['image' => $imageUrl],
                    ];
                }

                if (!empty($extra_data)) {
                    $message['message']['data'] = $extra_data;
                }

                try {
                    $response = $client->post($fcmUrl, [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json',
                        ],
                        'json' => $message,
                    ]);
                    return json_decode($response->getBody()->getContents(), true);
                } catch (ClientException $e) {
                    \Log::info("FCM topic push error: " . $e->getMessage());
                    return "Error:" . $e->getMessage();
                }
            }
        } catch (\Exception $e) {
            \Log::info("Firebase push exception: " . $e->getMessage());
            return $e->getMessage();
        }
    }
}
