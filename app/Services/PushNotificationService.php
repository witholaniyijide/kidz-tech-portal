<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PushNotificationService
{
    /**
     * FCM V1 API Endpoint
     */
    protected string $fcmUrl;

    /**
     * Path to service account JSON file
     */
    protected string $serviceAccountPath;

    /**
     * Project ID from Firebase
     */
    protected string $projectId = 'kidz-tech-portal';

    public function __construct()
    {
        $this->serviceAccountPath = storage_path('app/firebase/service-account.json');
        $this->fcmUrl = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
    }

    /**
     * Send push notification to a single user
     */
    public function sendToUser(User $user, string $title, string $body, array $data = []): bool
    {
        if (empty($user->push_token)) {
            Log::info('No push token for user', ['user_id' => $user->id]);
            return false;
        }

        return $this->send(
            $user->push_token,
            $title,
            $body,
            $data,
            $user->device_type
        );
    }

    /**
     * Send push notification to multiple users
     */
    public function sendToUsers(array $users, string $title, string $body, array $data = []): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'no_token' => 0,
        ];

        foreach ($users as $user) {
            if (empty($user->push_token)) {
                $results['no_token']++;
                continue;
            }

            $sent = $this->send(
                $user->push_token,
                $title,
                $body,
                $data,
                $user->device_type
            );

            if ($sent) {
                $results['success']++;
            } else {
                $results['failed']++;
            }
        }

        return $results;
    }

    /**
     * Send push notification to a topic (all subscribers)
     */
    public function sendToTopic(string $topic, string $title, string $body, array $data = []): bool
    {
        return $this->sendToTopicV1($topic, $title, $body, $data);
    }

    /**
     * Get OAuth2 access token for FCM V1 API
     */
    protected function getAccessToken(): ?string
    {
        // Cache the token for 55 minutes (tokens last 60 minutes)
        return Cache::remember('fcm_access_token', 55 * 60, function () {
            try {
                if (!file_exists($this->serviceAccountPath)) {
                    Log::error('Firebase service account file not found', [
                        'path' => $this->serviceAccountPath
                    ]);
                    return null;
                }

                $serviceAccount = json_decode(file_get_contents($this->serviceAccountPath), true);

                if (!$serviceAccount) {
                    Log::error('Invalid service account JSON');
                    return null;
                }

                // Create JWT
                $now = time();
                $header = [
                    'alg' => 'RS256',
                    'typ' => 'JWT'
                ];

                $payload = [
                    'iss' => $serviceAccount['client_email'],
                    'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                    'aud' => 'https://oauth2.googleapis.com/token',
                    'iat' => $now,
                    'exp' => $now + 3600
                ];

                $headerEncoded = $this->base64UrlEncode(json_encode($header));
                $payloadEncoded = $this->base64UrlEncode(json_encode($payload));

                $signatureInput = $headerEncoded . '.' . $payloadEncoded;

                // Sign with private key
                $privateKey = openssl_pkey_get_private($serviceAccount['private_key']);
                if (!$privateKey) {
                    Log::error('Failed to load private key');
                    return null;
                }

                openssl_sign($signatureInput, $signature, $privateKey, OPENSSL_ALGO_SHA256);
                $signatureEncoded = $this->base64UrlEncode($signature);

                $jwt = $signatureInput . '.' . $signatureEncoded;

                // Exchange JWT for access token
                $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    Log::info('FCM access token obtained successfully');
                    return $data['access_token'] ?? null;
                }

                Log::error('Failed to get access token', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return null;

            } catch (\Exception $e) {
                Log::error('Exception getting access token', [
                    'error' => $e->getMessage()
                ]);
                return null;
            }
        });
    }

    /**
     * Base64 URL encode
     */
    protected function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Send the actual FCM V1 API request
     */
    protected function send(string $token, string $title, string $body, array $data = [], ?string $deviceType = null): bool
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            Log::error('No access token available for FCM');
            return false;
        }

        try {
            $message = [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => array_map('strval', $data), // FCM requires string values
            ];

            // Add platform-specific config
            if ($deviceType === 'android' || $deviceType === 'web') {
                $message['android'] = [
                    'priority' => 'high',
                    'notification' => [
                        'sound' => 'default',
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    ],
                ];
            }

            if ($deviceType === 'ios') {
                $message['apns'] = [
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                            'badge' => 1,
                        ],
                    ],
                ];
            }

            // Web push config
            if ($deviceType === 'web') {
                $message['webpush'] = [
                    'notification' => [
                        'icon' => '/images/logo_light.png',
                        'badge' => '/images/favicon.png',
                    ],
                    'fcm_options' => [
                        'link' => config('app.url') . '/dashboard',
                    ],
                ];
            }

            $response = Http::withToken($accessToken)
                ->post($this->fcmUrl, ['message' => $message]);

            if ($response->successful()) {
                Log::info('Push notification sent successfully', [
                    'token' => substr($token, 0, 20) . '...',
                    'title' => $title,
                ]);
                return true;
            }

            $error = $response->json();
            Log::warning('FCM error', [
                'status' => $response->status(),
                'error' => $error,
            ]);

            // Handle invalid token
            if (isset($error['error']['details'])) {
                foreach ($error['error']['details'] as $detail) {
                    if (isset($detail['errorCode']) && 
                        in_array($detail['errorCode'], ['UNREGISTERED', 'INVALID_ARGUMENT'])) {
                        $this->invalidateToken($token);
                    }
                }
            }

            return false;

        } catch (\Exception $e) {
            Log::error('FCM exception', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send to topic using V1 API
     */
    protected function sendToTopicV1(string $topic, string $title, string $body, array $data = []): bool
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return false;
        }

        try {
            $message = [
                'topic' => $topic,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => array_map('strval', $data),
            ];

            $response = Http::withToken($accessToken)
                ->post($this->fcmUrl, ['message' => $message]);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('FCM topic exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Remove invalid push token from user
     */
    protected function invalidateToken(string $token): void
    {
        User::where('push_token', $token)->update([
            'push_token' => null,
            'device_type' => null,
        ]);

        Log::info('Invalidated push token', ['token' => substr($token, 0, 20) . '...']);
    }

    /**
     * Register/update a user's push token
     */
    public function registerToken(User $user, string $token, string $deviceType = 'web'): bool
    {
        try {
            $user->update([
                'push_token' => $token,
                'device_type' => $deviceType,
                'push_token_updated_at' => now(),
            ]);

            Log::info('Push token registered', [
                'user_id' => $user->id,
                'device_type' => $deviceType,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to register push token', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Unregister a user's push token (logout)
     */
    public function unregisterToken(User $user): bool
    {
        try {
            $user->update([
                'push_token' => null,
                'device_type' => null,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to unregister push token', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Clear cached access token (useful if you update the service account)
     */
    public function clearTokenCache(): void
    {
        Cache::forget('fcm_access_token');
    }
}
