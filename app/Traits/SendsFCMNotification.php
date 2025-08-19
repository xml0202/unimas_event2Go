<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Google\Auth\Credentials\ServiceAccountCredentials;

trait SendsFCMNotification
{
    public function sendNotificationUsingFCMHttpV1(array $roles, $title, $body, $eventId, $adminId = null)
    {
        $projectId = 'event2go-1329c'; // Replace with your Firebase project ID
    
        Log::info('Sending Notification', compact('roles', 'title', 'body', 'eventId', 'adminId'));
    
        $query = User::role($roles)
            ->whereNotNull('fcm_token')
            ->whereIn('id', function ($q) use ($eventId) {
                $q->select('user_id')
                  ->from('attendees')
                  ->where('event_id', $eventId);
            });
    
        if ($adminId) {
            $query->whereHas('events', function ($q) use ($eventId, $adminId) {
                $q->where('events.id', $eventId)
                  ->where('events.admin_id', $adminId);
            });
        }
    
        $tokens = $query->pluck('fcm_token')->toArray();
        Log::info('Filtered FCM Tokens', ['tokens' => $tokens]);
    
        if (empty($tokens)) {
            Log::warning("No FCM tokens found for event ID: $eventId");
            return;
        }
    
        $accessToken = $this->fetchAccessToken();
    
        $message = [
            'message' => [
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'android' => ['priority' => 'high'],
                'apns' => ['headers' => ['apns-priority' => '10']],
            ],
        ];
    
        foreach ($tokens as $token) {
            $message['message']['token'] = $token;
    
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
            ])->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", $message);
    
            if (!$response->successful()) {
                Log::error("FCM Error for token: $token", ['error' => $response->body()]);
            } else {
                Log::info("FCM Sent to token: $token", ['response' => $response->body()]);
            }
        }
    }


    public function fetchAccessToken()
    {
        $credentialsPath = env('GOOGLE_APPLICATION_CREDENTIALS');
        
        if (!file_exists($credentialsPath)) {
            Log::error("Service account credentials file does not exist at: " . $credentialsPath);
            return null;
        }
    
        try {
            $scopes = ['https://www.googleapis.com/auth/firebase.messaging']; 
    
            $credentials = new ServiceAccountCredentials(
                $scopes,
                $credentialsPath
            );
        
            $token = $credentials->fetchAuthToken();
        
            if (isset($token['access_token'])) {
                return $token['access_token'];
            } else {
                Log::error("Failed to fetch access token", ['token' => $token]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error("Error fetching access token", [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

}
