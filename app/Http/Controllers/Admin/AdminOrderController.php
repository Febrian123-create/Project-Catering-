<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user', 'orderDetails.menu');

        if ($request->filled('metode')) {
            $query->where('metode_pengantaran', $request->metode);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tgl_pesan', $request->tanggal);
        }

        $orders = $query->orderBy('tgl_pesan', 'desc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, $order_id)
    {
        $request->validate([
            'status_pesanan' => 'required|in:perlu dikirim,dalam pengiriman,terkirim',
        ]);

        $order = Order::findOrFail($order_id);
        $order->status_pesanan = $request->status_pesanan;
        $order->save();

        return redirect()->back()->with('success', 'Status pesanan #' . $order_id . ' berhasil diperbarui menjadi ' . $request->status_pesanan);
    }
}
