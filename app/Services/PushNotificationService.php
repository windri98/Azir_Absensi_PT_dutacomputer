<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    public function sendToExpo(string $token, string $title, string $body, array $data = []): bool
    {
        if (! $this->isValidExpoToken($token)) {
            Log::warning('Invalid Expo push token', ['token' => $token]);
            return false;
        }

        $payload = [
            'to' => $token,
            'sound' => 'default',
            'title' => $title,
            'body' => $body,
            'data' => $data,
        ];

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('https://exp.host/--/api/v2/push/send', $payload);

            if (! $response->successful()) {
                Log::warning('Expo push failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('Expo push error: ' . $e->getMessage());
            return false;
        }
    }

    private function isValidExpoToken(string $token): bool
    {
        return str_starts_with($token, 'ExponentPushToken[')
            || str_starts_with($token, 'ExpoPushToken[');
    }
}
