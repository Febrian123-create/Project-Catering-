<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected ?string $token;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
    }

    /**
     * Normalize phone number to international format (62...)
     *
     * @param string $number
     * @return string
     */
    public function normalizePhoneNumber(string $number): string
    {
        // Remove non-numeric characters
        $number = preg_replace('/[^0-9]/', '', $number);

        // If it starts with 0, replace with 62
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }

        // If it starts with 8 (local format but without leading 0), add 62
        if (str_starts_with($number, '8')) {
            $number = '62' . $number;
        }

        return $number;
    }

    /**
     * Send OTP via WhatsApp using Fonnte API
     *
     * @param string $target
     * @param string|int $otp
     * @return bool
     */
    public function sendOTP(string $target, $otp): bool
    {
        if (empty($this->token)) {
            Log::error('WhatsAppService: FONNTE_TOKEN is not configured.');
            return false;
        }

        $normalizedTarget = $this->normalizePhoneNumber($target);
        $message = "Kode OTP Bento kamu adalah: $otp. Jangan beritahu siapapun ya!";

        Log::info("WhatsAppService: Sending OTP to $normalizedTarget (original: $target)");

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token
            ])->post('https://api.fonnte.com/send', [
                'target' => $normalizedTarget,
                'message' => $message,
            ]);

            $result = $response->json();

            if ($response->successful() && ($result['status'] ?? false) === true) {
                Log::info("WhatsAppService: OTP successfully sent to $normalizedTarget. Response: " . json_encode($result));
                return true;
            }

            Log::error("WhatsAppService: Failed to send OTP to $normalizedTarget. Response: " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error("WhatsAppService: Exception during OTP send: " . $e->getMessage());
            return false;
        }
    }
}
