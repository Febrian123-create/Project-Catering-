<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function export(Request $request): StreamedResponse
    {
        $query = Order::with('user', 'orderDetails.menu.product')
            ->orderBy('tgl_pesan', 'desc');

        if ($request->filled('metode')) {
            $query->where('metode_pengantaran', $request->metode);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tgl_pesan', $request->tanggal);
        }

        $filename = 'pesanan_dosinyam_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
        ];

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // BOM for Excel UTF-8 compatibility
            fputs($handle, "\xEF\xBB\xBF");

            // Header row
            fputcsv($handle, [
                'Order ID',
                'Tanggal Pesan',
                'Nama Pembeli',
                'No. HP',
                'Item Pesanan',
                'Metode Pengantaran',
                'Jam Ambil',
                'Alamat / Lokasi',
                'Status Pembayaran',
                'Status Pesanan',
                'Total (Rp)',
            ]);

            $query->chunk(200, function ($orders) use ($handle) {
                foreach ($orders as $order) {
                    $items = $order->orderDetails->map(function ($d) {
                        $name = $d->menu->product->nama ?? 'Menu';
                        return $name . ' x' . $d->qty;
                    })->implode(' | ');

                    fputcsv($handle, [
                        $order->order_id,
                        $order->tgl_pesan->format('d/m/Y'),
                        $order->user->nama ?? '-',
                        $order->user->kontak ?? '-',
                        $items,
                        $order->metode_pengantaran === 'ambil_eureka' ? 'Ambil di Eureka' : 'Antar ke Alamat',
                        $order->jam_pengambilan ?? '-',
                        $order->alamat_pengiriman ?? '-',
                        ucfirst($order->status_pembayaran),
                        $order->status_pesanan ?? 'pending',
                        $order->total_bayar,
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
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
