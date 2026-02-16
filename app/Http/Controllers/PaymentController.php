<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function notification(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans Notification Receive: ', $payload);

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;

        // Strip the timestamp from order_id if it was added for uniqueness
        // Structure: ORDER_ID-TIMESTAMP
        $cleanOrderId = explode('-', $orderId)[0];

        $order = Order::where('order_id', $cleanOrderId)->first();

        if (!$order) {
            Log::error('Order not found for Midtrans notification: ' . $cleanOrderId);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Verify Signature (Recommended for real product)
        // For Sandbox/Dev, we can skip or implement if we have the key
        $signatureKey = hash("sha512", $orderId . $payload['status_code'] . $grossAmount . config('services.midtrans.server_key'));
        if ($signatureKey !== $payload['signature_key']) {
             Log::warning('Midtrans Invalid Signature: ' . $orderId);
             return response()->json(['message' => 'Invalid signature'], 403);
        }

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $order->update(['status_pembayaran' => 'pending']);
            } else if ($fraudStatus == 'accept') {
                $order->update(['status_pembayaran' => 'paid']);
                Notification::create([
                    'user_id' => $order->user_id,
                    'title' => 'Pembayaran Berhasil ✅',
                    'message' => 'Pembayaran untuk pesanan #' . $order->order_id . ' senilai Rp ' . number_format($order->total_bayar, 0, ',', '.') . ' telah diterima.',
                    'is_read' => false,
                ]);
            }
        } else if ($transactionStatus == 'settlement') {
            $order->update(['status_pembayaran' => 'paid']);
            Notification::create([
                'user_id' => $order->user_id,
                'title' => 'Pembayaran Berhasil ✅',
                'message' => 'Pembayaran untuk pesanan #' . $order->order_id . ' senilai Rp ' . number_format($order->total_bayar, 0, ',', '.') . ' telah diterima.',
                'is_read' => false,
            ]);
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $order->update(['status_pembayaran' => 'cancelled']);
            Notification::create([
                'user_id' => $order->user_id,
                'title' => 'Pembayaran Gagal ❌',
                'message' => 'Pembayaran untuk pesanan #' . $order->order_id . ' gagal atau dibatalkan. Silakan coba lagi.',
                'is_read' => false,
            ]);
        } else if ($transactionStatus == 'pending') {
            $order->update(['status_pembayaran' => 'pending']);
        }

        return response()->json(['message' => 'Notification processed']);
    }
}
