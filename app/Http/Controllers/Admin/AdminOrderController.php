<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->orderBy('tgl_pesan', 'desc')->paginate(10);
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
