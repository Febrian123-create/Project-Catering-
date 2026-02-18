@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="sticker-container">
    <i class="bi bi-receipt sticker sticker-1"></i>
    <i class="bi bi-box-seam sticker sticker-2"></i>
    <i class="bi bi-clock-history sticker sticker-3"></i>
    <i class="bi bi-patch-check sticker sticker-4"></i>
    <i class="bi bi-brightness-high sticker sticker-5"></i>
</div>

<div class="container py-5">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-5 gap-3">
        <div class="d-flex align-items-center">
            <div class="bg-warning border border-2 border-dark p-3 rounded-circle me-3 shadow-sm">
                <i class="bi bi-clock-history h3 mb-0 text-dark"></i>
            </div>
            <div>
                <h2 class="section-title mb-1">Pesanan Saya</h2>
                <p class="text-muted mb-0 fw-bold">Lacak dan lihat riwayat pesanan catering lezat Anda.</p>
            </div>
        </div>
        <a href="{{ route('menus.index') }}" class="brand-btn brand-btn-primary text-decoration-none shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Pesan Baru
        </a>
    </div>

    @if($orders->count() > 0)
        <div class="row g-4">
            @foreach($orders as $index => $order)
                @php
                    $card_styles = ['brand-card-blue', 'brand-card-green', 'brand-card-purple', ''];
                    $status_badge_class = '';
                    if($order->status_pembayaran == 'pending') $status_badge_class = 'bg-warning text-dark';
                    elseif($order->status_pembayaran == 'paid') $status_badge_class = 'bg-success text-white';
                    elseif($order->status_pembayaran == 'cancelled') $status_badge_class = 'bg-danger text-white';
                @endphp
                <div class="col-12">
                    <div class="brand-card {{ $card_styles[$index % 4] }} p-4">
                        <div class="row align-items-center g-4">
                            <div class="col-md-3">
                                <div class="ps-3 border-start border-4 border-dark">
                                    <div class="small text-muted mb-1 fw-bold text-uppercase tracking-wider">Order ID</div>
                                    <h5 class="fw-bold text-dark mb-2">#{{ $order->order_id }}</h5>
                                    <span class="badge {{ $status_badge_class }} rounded-pill border border-dark px-3 py-2 fw-bold shadow-sm">
                                        {{ ucfirst($order->status_pembayaran) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="small text-muted mb-1 fw-bold">Tanggal Pesan</div>
                                <h6 class="fw-bold mb-0 text-dark fs-5">{{ $order->tgl_pesan->format('d F Y') }}</h6>
                            </div>
                            <div class="col-md-3">
                                <div class="small text-muted mb-1 fw-bold">Items</div>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light border border-2 border-dark rounded-4 p-2 me-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-box-seam text-dark fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">{{ $order->orderDetails->count() }} Menu</h6>
                                        <small class="text-muted fw-bold">{{ $order->orderDetails->sum('qty') }} Porsi</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="small text-muted mb-1 fw-bold">Total Tagihan</div>
                                <h6 class="fw-bold mb-0 text-danger fs-5">{{ $order->formatted_total }}</h6>
                            </div>
                            <div class="col-md-1 text-md-end">
                                <a href="{{ route('orders.show', $order) }}" class="brand-btn brand-btn-primary rounded-circle p-0 d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px;">
                                    <i class="bi bi-arrow-right fs-4"></i>
                                </a>
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
        <div class="brand-card py-5 px-4 text-center">
            <div class="mb-4">
                <i class="bi bi-receipt display-1 text-muted opacity-25"></i>
            </div>
            <h3 class="fw-bold text-dark">Belum Ada Pesanan</h3>
            <p class="text-muted fw-bold mb-4">Anda belum pernah melakukan pemesanan catering.</p>
            <div>
                <a href="{{ route('menus.index') }}" class="brand-btn brand-btn-primary text-white text-decoration-none px-5 py-3">
                    <i class="bi bi-cart-plus me-2"></i>Mulai Belanja Sekarang
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
