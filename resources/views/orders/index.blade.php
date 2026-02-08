@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-clock-history me-2"></i>Riwayat Pesanan</h2>

    <div class="card">
        <div class="card-body">
            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th class="text-center">Status</th>
                                <th>Item</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-primary">{{ $order->order_id }}</span>
                                    </td>
                                    <td>
                                        {{ $order->tgl_pesan->format('d M Y') }}
                                    </td>
                                    <td>
                                        {{ $order->formatted_total }}
                                    </td>
                                    <td class="text-center">
                                        {!! $order->status_badge !!}
                                    </td>
                                    <td>
                                        <ul class="list-unstyled mb-0 small">
                                            @foreach($order->orderDetails->take(2) as $detail)
                                                <li>- {{ $detail->menu->product->nama }} (x{{ $detail->qty }})</li>
                                            @endforeach
                                            @if($order->orderDetails->count() > 2)
                                                <li class="text-muted">+ {{ $order->orderDetails->count() - 2 }} item lainnya</li>
                                            @endif
                                        </ul>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-receipt display-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">Belum ada pesanan</h4>
                    <p class="text-muted">Ayo mulai pesan sekarang!</p>
                    <a href="{{ route('menus.index') }}" class="btn btn-primary">
                        <i class="bi bi-search me-2"></i>Cari Menu
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
