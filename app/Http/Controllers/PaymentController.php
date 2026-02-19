<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Notification;
use App\Services\DokuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Handle DOKU HTTP Notification (Webhook).
     * DOKU sends POST to this endpoint when payment status changes.
     */
    public function notification(Request $request)
    {
        $notificationBody = $request->getContent();
        $payload = json_decode($notificationBody, true);
        Log::info('DOKU Notification Received: ', $payload ?? []);

        // Get headers from DOKU
        $clientId = $request->header('Client-Id', '');
        $requestId = $request->header('Request-Id', '');
        $requestTimestamp = $request->header('Request-Timestamp', '');
        $receivedSignature = $request->header('Signature', '');

        // Verify signature
        $dokuService = new DokuService();
        $notificationPath = '/payment/doku-notification';

        $isValid = $dokuService->verifyNotificationSignature(
            $clientId,
            $requestId,
            $requestTimestamp,
            $notificationPath,
            $notificationBody,
            $receivedSignature
        );

        if (!$isValid) {
            Log::warning('DOKU Invalid Signature for notification');
            return response('Invalid Signature', 400);
        }

        // Extract order data from notification
        $invoiceNumber = $payload['order']['invoice_number'] ?? null;
        $transactionStatus = $payload['transaction']['status'] ?? null;

        if (!$invoiceNumber) {
            Log::error('DOKU notification missing invoice_number');
            return response('Missing invoice_number', 400);
        }

        // Strip the timestamp suffix: ORDER_ID-TIMESTAMP
        $parts = explode('-', $invoiceNumber);
        // order_id format: ORD000000001 (no dash), so the last part after dash is the timestamp
        array_pop($parts);
        $cleanOrderId = implode('-', $parts);

        $order = Order::where('order_id', $cleanOrderId)->first();

        if (!$order) {
            Log::error('Order not found for DOKU notification: ' . $cleanOrderId);
            return response('Order not found', 404);
        }

        // For Checkout: IGNORE "FAILED" status (DOKU best practice)
        if ($transactionStatus === 'SUCCESS') {
            $this->markOrderAsPaid($order);
        }

        return response('OK', 200);
    }

    /**
     * Handle callback redirect after payment (user is redirected here by DOKU).
     * This is the main mechanism for updating payment status on localhost.
     */
    public function callback(Request $request)
    {
        $orderId = $request->query('order_id');
        Log::info('DOKU Callback received for order: ' . ($orderId ?? 'unknown'));

        if ($orderId) {
            $order = Order::where('order_id', $orderId)->first();
            if ($order && $order->status_pembayaran === 'pending') {
                // User was redirected from DOKU after successful payment
                $this->markOrderAsPaid($order);
                return redirect()->route('orders.show', $order)
                    ->with('success', 'Pembayaran berhasil! Terima kasih. 🎉');
            }
        }

        return redirect()->route('orders.index')
            ->with('success', 'Terima kasih! Pembayaran Anda sedang diproses.');
    }

    /**
     * Handle callback when user cancels payment.
     */
    public function callbackCancel(Request $request)
    {
        $orderId = $request->query('order_id');
        Log::info('DOKU Callback Cancel received for order: ' . ($orderId ?? 'unknown'));

        if ($orderId) {
            return redirect()->route('orders.show', ['order' => $orderId])
                ->with('info', 'Pembayaran dibatalkan. Anda dapat mencoba lagi.');
        }

        return redirect()->route('orders.index')
            ->with('info', 'Pembayaran dibatalkan. Anda dapat mencoba lagi dari halaman pesanan.');
    }

    /**
     * AJAX endpoint: Check payment status via DOKU API.
     * Called by the order show page to auto-update status.
     */
    public function checkStatus(Request $request, $orderId)
    {
        $order = Order::where('order_id', $orderId)->first();

        if (!$order) {
            return response()->json(['status' => 'not_found'], 404);
        }

        // Already paid? Return immediately
        if ($order->status_pembayaran === 'paid') {
            return response()->json(['status' => 'paid', 'is_paid' => true]);
        }

        // Has invoice_number? Check with DOKU API
        if ($order->invoice_number) {
            $dokuService = new DokuService();
            $dokuStatus = $dokuService->checkPaymentStatus($order->invoice_number);

            Log::info('DOKU status check for ' . $order->order_id . ': ' . ($dokuStatus ?? 'null'));

            if ($dokuStatus === 'SUCCESS') {
                $this->markOrderAsPaid($order);
                return response()->json(['status' => 'paid', 'is_paid' => true]);
            }
        }

        return response()->json([
            'status' => $order->status_pembayaran,
            'is_paid' => false,
        ]);
    }

    /**
     * Mark an order as paid and send notification.
     */
    private function markOrderAsPaid(Order $order): void
    {
        if ($order->status_pembayaran === 'paid') {
            return; // Already paid, idempotent
        }

        $order->update(['status_pembayaran' => 'paid']);

        Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Pembayaran Berhasil ✅',
            'message' => 'Pembayaran untuk pesanan #' . $order->order_id . ' senilai Rp ' . number_format($order->total_bayar, 0, ',', '.') . ' telah diterima.',
            'is_read' => false,
        ]);

        Log::info('Order ' . $order->order_id . ' marked as paid.');
    }
}
