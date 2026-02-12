<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public function getSnapToken($order)
    {
        $serverKey = config('services.midtrans.server_key');
        $isProduction = config('services.midtrans.is_production');
        $baseUrl = $isProduction 
            ? 'https://app.midtrans.com/snap/v1/transactions' 
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        if (empty($serverKey)) {
            Log::warning('Midtrans Server Key is empty. Returning dummy token.');
            return 'DUMMY_SNAP_TOKEN_' . $order->order_id;
        }

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_id . '-' . time(), // unique order_id for Midtrans
                'gross_amount' => (int) $order->total_bayar,
            ],
            'customer_details' => [
                'first_name' => $order->user->nama ?? 'Customer',
                'email' => $order->user->email ?? '',
                'phone' => $order->user->no_hp ?? '',
            ],
            'item_details' => $order->orderDetails->map(function($detail) {
                return [
                    'id' => $detail->menu_id,
                    'price' => (int) $detail->menu->product->harga,
                    'quantity' => (int) $detail->qty,
                    'name' => $detail->menu->product->nama,
                ];
            })->toArray(),
        ];

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->withBasicAuth($serverKey, '')
              ->post($baseUrl, $params);

            if ($response->successful()) {
                return $response->json()['token'];
            }

            Log::error('Midtrans API Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Midtrans Exception: ' . $e->getMessage());
            return null;
        }
    }
}
