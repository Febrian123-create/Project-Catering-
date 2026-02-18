@extends('layouts.app')

@section('title', 'Kelola Pesanan')

@section('content')
<div class="sticker-container">
    <i class="bi bi-receipt sticker sticker-1"></i>
    <i class="bi bi-truck sticker sticker-2"></i>
    <i class="bi bi-check2-all sticker sticker-3"></i>
    <i class="bi bi-cash-stack sticker sticker-4"></i>
    <i class="bi bi-person-badge sticker sticker-5"></i>
</div>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title mb-0">Kelola Pesanan</h2>
    </div>

    <div class="brand-card brand-card-blue">
        <div class="card-body p-0">
            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table brand-table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Pembeli</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th class="text-center">Status Bayar</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <code class="fw-bold text-dark bg-light px-2 py-1 rounded border border-dark">#{{ $order->order_id }}</code>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $order->user->nama ?? 'Guest' }}</div>
                                        <div class="small text-muted">{{ $order->user->no_telp ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-calendar-event me-2 text-primary"></i>
                                            <span>{{ $order->tgl_pesan->format('d M Y') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary">{{ $order->formatted_total }}</span>
                                    </td>
                                    <td class="text-center">
                                        {!! $order->status_badge !!}
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('orders.show', $order) }}" class="brand-btn brand-btn-warning btn-sm text-decoration-none" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            {{-- Status Update Form --}}
                                            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <select name="status_pembayaran" onchange="this.form.submit()" class="form-select form-select-sm rounded-pill border-dark shadow-sm d-inline-block w-auto">
                                                    <option value="pending" {{ $order->status_pembayaran == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="paid" {{ $order->status_pembayaran == 'paid' ? 'selected' : '' }}>Paid</option>
                                                    <option value="cancelled" {{ $order->status_pembayaran == 'cancelled' ? 'selected' : '' }}>Cancel</option>
                                                </select>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-top border-2 border-dark">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted opacity-25 mb-3 d-block"></i>
                    <p class="text-muted fs-5">Belum ada pesanan yang masuk.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
