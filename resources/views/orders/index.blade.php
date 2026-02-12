@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@push('styles')
<style>
    .order-card {
        border: none;
        border-radius: 20px;
        background: white;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    .status-section {
        border-left: 4px solid #eee;
        padding-left: 20px;
    }
    .status-pending { border-color: var(--fh-yellow); }
    .status-paid { border-color: var(--fh-green); }
    .status-cancelled { border-color: var(--fh-red); }
    
    .item-thumbnail {
        width: 50px;
        height: 50px;
        background: #f8f9fa;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-5">
        <div class="d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3 text-primary">
                <i class="bi bi-clock-history h3 mb-0"></i>
            </div>
            <div>
                <h2 class="fw-bold mb-0">Pesanan Saya</h2>
                <p class="text-muted mb-0">Lacak dan lihat riwayat pesanan catering Anda.</p>
            </div>
        </div>
        <a href="{{ route('menus.index') }}" class="btn btn-outline-primary rounded-pill px-4">
            <i class="bi bi-plus-lg me-2"></i>Pesan Baru
        </a>
    </div>

    @if($orders->count() > 0)
        <div class="row g-4">
            @foreach($orders as $order)
                <div class="col-12">
                    <div class="card order-card shadow-sm">
                        <div class="card-body p-4">
                            <div class="row align-items-center g-4">
                                <div class="col-md-3">
                                    <div class="status-section status-{{ $order->status_pembayaran }}">
                                        <div class="small text-muted mb-1 text-uppercase tracking-wider">Order ID</div>
                                        <h6 class="fw-bold text-primary mb-2">#{{ $order->order_id }}</h6>
                                        {!! $order->status_badge !!}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small text-muted mb-1">Tanggal Pesan</div>
                                    <h6 class="fw-bold mb-0 text-black">{{ $order->tgl_pesan->format('d F Y') }}</h6>
                                </div>
                                <div class="col-md-3">
                                    <div class="small text-muted mb-1">Items</div>
                                    <div class="d-flex align-items-center">
                                        <div class="item-thumbnail me-2">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0 text-black">{{ $order->orderDetails->count() }} Menu</h6>
                                            <small class="text-muted">{{ $order->orderDetails->sum('qty') }} Porsi</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="small text-muted mb-1">Total</div>
                                    <h6 class="fw-bold mb-0 text-primary">{{ $order->formatted_total }}</h6>
                                </div>
                                <div class="col-md-1 text-md-end">
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-primary rounded-circle p-2 shadow-sm" style="width: 45px; height: 45px;">
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-5 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    @else
        <div class="card order-card shadow-sm py-5 px-4 text-center">
            <div class="mb-4">
                <i class="bi bi-receipt display-1 text-muted opacity-25"></i>
            </div>
            <h4 class="fw-bold">Belum Ada Pesanan</h4>
            <p class="text-muted mb-4">Anda belum pernah melakukan pemesanan catering.</p>
            <div>
                <a href="{{ route('menus.index') }}" class="btn btn-primary px-5 py-3 rounded-pill fw-bold">
                    Mulai Belanja Sekarang
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
