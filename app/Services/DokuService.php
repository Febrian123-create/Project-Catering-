<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DokuService
{
    protected string $clientId;
    protected string $secretKey;
    protected bool $isProduction;
    protected string $baseUrl;

    public function __construct()
    {
        $this->clientId = config('services.doku.client_id');
        $this->secretKey = config('services.doku.secret_key');
        $this->isProduction = config('services.doku.is_production');
        $this->baseUrl = $this->isProduction
            ? 'https://api.doku.com'
            : 'https://api-sandbox.doku.com';
    }

    /**
     * Get the DOKU Checkout payment URL for an order.
     * Stores the invoice_number in the order record for status checking.
     */
    public function getPaymentUrl(Order $order): ?string
    {
        Log::info('DOKU getPaymentUrl called for order: ' . $order->order_id);

        if (empty($this->clientId) || empty($this->secretKey)) {
            Log::warning('DOKU credentials empty.');
            return null;
        }

        // Reuse existing invoice_number if order already has one
        $invoiceNumber = $order->invoice_number ?: ($order->order_id . '-' . time());

        $requestId = (string) Str::uuid();
        $requestTimestamp = gmdate('Y-m-d\TH:i:s\Z');
        $requestTarget = '/checkout/v1/payment';

        $body = [
            'order' => [
                'amount' => (int) $order->total_bayar,
                'invoice_number' => $invoiceNumber,
                'currency' => 'IDR',
                'callback_url' => route('payment.callback', ['order_id' => $order->order_id]),
                'callback_url_cancel' => route('payment.callback.cancel', ['order_id' => $order->order_id]),
                'language' => 'ID',
                'auto_redirect' => true,
                'line_items' => $order->orderDetails->map(function ($detail) {
                    return [
                        'id' => $detail->menu_id,
                        'name' => substr($detail->menu->product->nama ?? 'Menu Item', 0, 255),
                        'quantity' => (int) $detail->qty,
                        'price' => (int) ($detail->menu->product->harga ?? 0),
                    ];
                })->toArray(),
            ],
            'payment' => [
                'payment_due_date' => 60,
            ],
            'customer' => [
                'id' => (string) $order->user_id,
                'name' => $order->user->nama ?? 'Customer',
                'phone' => $order->user->kontak ?? '',
                'address' => $order->alamat_pengiriman ?? '',
                'country' => 'ID',
            ],
        ];

        $jsonBody = json_encode($body);
        $digest = base64_encode(hash('sha256', $jsonBody, true));
        $signature = $this->generateSignature($this->clientId, $requestId, $requestTimestamp, $requestTarget, $digest);

        try {
            $response = Http::withHeaders([
                'Client-Id' => $this->clientId,
                'Request-Id' => $requestId,
                'Request-Timestamp' => $requestTimestamp,
                'Signature' => $signature,
                'Content-Type' => 'application/json',
            ])->withBody($jsonBody, 'application/json')
              ->post($this->baseUrl . $requestTarget);

            Log::info('DOKU Response: ' . $response->status() . ' ' . $response->body());

            if ($response->successful()) {
                $data = $response->json();
                $paymentUrl = $data['response']['payment']['url'] ?? null;

                if ($paymentUrl) {
                    // Save invoice_number to order for status checking later
                    $order->update(['invoice_number' => $invoiceNumber]);
                    Log::info('DOKU Payment URL generated, invoice: ' . $invoiceNumber);
                    return $paymentUrl;
                }

                Log::error('DOKU response missing payment URL');
                return null;
            }

            Log::error('DOKU API Error [' . $response->status() . ']: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('DOKU Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check payment status via DOKU Check Status API.
     * Returns: 'SUCCESS', 'PENDING', 'FAILED', or null on error.
     */
    public function checkPaymentStatus(string $invoiceNumber): ?string
    {
        if (empty($this->clientId) || empty($this->secretKey)) {
            return null;
        }

        $requestId = (string) Str::uuid();
        $requestTimestamp = gmdate('Y-m-d\TH:i:s\Z');
        $requestTarget = '/orders/v1/status/' . $invoiceNumber;

        // For GET requests, use empty body digest
        $digest = base64_encode(hash('sha256', '', true));
        $signature = $this->generateSignature($this->clientId, $requestId, $requestTimestamp, $requestTarget, $digest);

        try {
            $response = Http::withHeaders([
                'Client-Id' => $this->clientId,
                'Request-Id' => $requestId,
                'Request-Timestamp' => $requestTimestamp,
                'Signature' => $signature,
            ])->get($this->baseUrl . $requestTarget);

            Log::info('DOKU Check Status [' . $invoiceNumber . ']: ' . $response->status() . ' ' . $response->body());

            if ($response->successful()) {
                $data = $response->json();
                // DOKU returns transaction status
                return $data['transaction']['status'] ?? ($data['order']['status'] ?? null);
            }

            Log::error('DOKU Check Status Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('DOKU Check Status Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate HMAC-SHA256 signature for DOKU API request.
     */
    public function generateSignature(
        string $clientId,
        string $requestId,
        string $requestTimestamp,
        string $requestTarget,
        string $digest
    ): string {
        $rawSignature = "Client-Id:" . $clientId . "\n"
            . "Request-Id:" . $requestId . "\n"
            . "Request-Timestamp:" . $requestTimestamp . "\n"
            . "Request-Target:" . $requestTarget . "\n"
            . "Digest:" . $digest;

        $signature = base64_encode(
            hash_hmac('sha256', $rawSignature, $this->secretKey, true)
        );

        return 'HMACSHA256=' . $signature;
    }

    /**
     * Verify the signature from DOKU HTTP Notification.
     */
    public function verifyNotificationSignature(
        string $clientId,
        string $requestId,
        string $requestTimestamp,
        string $notificationTarget,
        string $notificationBody,
        string $receivedSignature
    ): bool {
        $digest = base64_encode(hash('sha256', $notificationBody, true));

        $rawSignature = "Client-Id:" . $clientId . "\n"
            . "Request-Id:" . $requestId . "\n"
            . "Request-Timestamp:" . $requestTimestamp . "\n"
            . "Request-Target:" . $notificationTarget . "\n"
            . "Digest:" . $digest;

        $calculatedSignature = base64_encode(
            hash_hmac('sha256', $rawSignature, $this->secretKey, true)
        );

        $finalSignature = 'HMACSHA256=' . $calculatedSignature;

        return hash_equals($finalSignature, $receivedSignature);
    }
}
