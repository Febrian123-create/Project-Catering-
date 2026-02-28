<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Notification;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('orderDetails.menu.product')
            ->where('user_id', Auth::id())
            ->orderBy('tgl_pesan', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $cartItems = Cart::with('menu.product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kosong!');
        }

        $total = $cartItems->sum('subtotal');

        return view('orders.create', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        \Log::info('OrderController@store called');
        $validated = $request->validate([
            'metode_pengantaran' => 'required|in:antar_alamat,ambil_eureka',
            'alamat_pengiriman' => 'required_if:metode_pengantaran,antar_alamat|nullable|string|max:255',
            'jam_pengambilan' => 'required_if:metode_pengantaran,ambil_eureka|nullable|string|in:08.30-09.30,11.30-12.30',
            'notes' => 'nullable|string|max:100',
        ]);

        $cartItems = Cart::with('menu.product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kosong!');
        }

        DB::beginTransaction();
        try {
            $total = $cartItems->sum('subtotal');

            $order = new Order();
            $order->order_id = Order::generateOrderId();
            $order->user_id = Auth::id();
            $order->metode_pengantaran = $validated['metode_pengantaran'];
            $order->alamat_pengiriman = $validated['metode_pengantaran'] === 'antar_alamat' ? $validated['alamat_pengiriman'] : 'Ambil di Eureka (Gedung G)';
            $order->jam_pengambilan = $validated['metode_pengantaran'] === 'ambil_eureka' ? $validated['jam_pengambilan'] : null;
            $order->notes = $validated['notes'] ?? '';
            $order->tgl_pesan = now();
            $order->total_bayar = $total;
            $order->status_pembayaran = 'pending';
            $order->save();

            foreach ($cartItems as $item) {
                $detail = new OrderDetail();
                $detail->detail_id = OrderDetail::generateDetailId();
                $detail->order_id = $order->order_id;
                $detail->menu_id = $item->menu_id;
                $detail->qty = $item->qty;
                $detail->tanggal_kirim = $item->menu->tgl_tersedia ?? now()->toDateString();
                $detail->status_kirim = 'pending';
                $detail->bundle_id = $item->bundle_id;
                $detail->bundle_name = $item->bundle_name;
                $detail->bundle_price = $item->bundle_price;
                $detail->save();
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            // Notify buyer
            Notification::create([
                'user_id' => Auth::id(),
                'title' => 'Pesanan Berhasil Dibuat',
                'message' => 'Pesanan #' . $order->order_id . ' senilai Rp ' . number_format($total, 0, ',', '.') . ' berhasil dibuat. Silakan lakukan pembayaran.',
                'is_read' => false,
            ]);

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('OrderController@store ERROR: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $order->load('orderDetails.menu.product', 'user');

        $paymentUrl = null;
        if ($order->status_pembayaran === 'pending') {
            $dokuService = new \App\Services\DokuService();
            $paymentUrl = $dokuService->getPaymentUrl($order);
        }

        return view('orders.show', compact('order', 'paymentUrl'));
    }

    // Admin management methods
    public function adminIndex()
    {
        $orders = Order::with('orderDetails.menu.product', 'user')
            ->orderBy('tgl_pesan', 'desc')
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status_pembayaran' => 'required|in:pending,paid,cancelled',
        ]);

        $order->update($validated);

        // Notify buyer about status change
        $statusLabel = match($validated['status_pembayaran']) {
            'paid' => 'Pembayaran Diterima ✅',
            'cancelled' => 'Pesanan Dibatalkan ❌',
            default => 'Status Diperbarui',
        };
        Notification::create([
            'user_id' => $order->user_id,
            'title' => $statusLabel,
            'message' => 'Pesanan #' . $order->order_id . ' — ' . $statusLabel,
            'is_read' => false,
        ]);

        return redirect()->back()
            ->with('success', 'Status pesanan berhasil diupdate!');
    }

    public function updateShipping(Request $request, OrderDetail $orderDetail)
    {
        $validated = $request->validate([
            'status_kirim' => 'required|in:pending,shipped,delivered',
            'tanggal_kirim' => 'nullable|date',
        ]);

        $orderDetail->update($validated);

        // Notify buyer about shipping update
        $order = Order::where('order_id', $orderDetail->order_id)->first();
        if ($order) {
            $shippingLabel = match($validated['status_kirim']) {
                'shipped' => 'Pesanan Dikirim 🚚',
                'delivered' => 'Pesanan Diterima 📦',
                default => 'Status Pengiriman Diperbarui',
            };
            Notification::create([
                'user_id' => $order->user_id,
                'title' => $shippingLabel,
                'message' => 'Pesanan #' . $order->order_id . ' — ' . $shippingLabel,
                'is_read' => false,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Status pengiriman berhasil diupdate!');
    }
}
