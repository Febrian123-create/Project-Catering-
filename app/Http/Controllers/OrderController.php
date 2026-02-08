<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
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
        $validated = $request->validate([
            'alamat_pengiriman' => 'required|string|max:120',
            'notes' => 'nullable|string|max:100',
            // 'tipe_pesan' removed as it's not in DB
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
            $order->alamat_pengiriman = $validated['alamat_pengiriman'];
            $order->notes = $validated['notes'];
            $order->tgl_pesan = now();
            $order->total_bayar = $total;
            $order->status_pembayaran = 'pending';
            $order->save();

            foreach ($cartItems as $item) {
                $detail = new OrderDetail();
                $detail->detail_id = OrderDetail::generateDetailId();
                $detail->order_id = $order->order_id;
                $detail->menu_id = $item->menu_id;
                // OrderDetail table in DB has qty, tanggal_kirim, status_kirim
                // But it does NOT have product_id, it has menu_id.
                $detail->qty = $item->qty;
                $detail->status_kirim = 'pending';
                $detail->save();
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()->isSeller()) {
            abort(403);
        }

        $order->load('orderDetails.menu.product', 'user');

        return view('orders.show', compact('order'));
    }

    // Seller methods
    public function sellerIndex()
    {
        $orders = Order::with('orderDetails.menu.product', 'user')
            ->orderBy('tgl_pesan', 'desc')
            ->paginate(15);

        return view('seller.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status_pembayaran' => 'required|in:pending,paid,cancelled',
        ]);

        $order->update($validated);

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

        return redirect()->back()
            ->with('success', 'Status pengiriman berhasil diupdate!');
    }
}
