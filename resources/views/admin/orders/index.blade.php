@extends('layouts.app')

@section('title', 'Admin - Manajemen Pesanan')

@section('content')
<div class="sticker-container">
    <i class="bi bi-box-seam sticker sticker-1"></i>
    <i class="bi bi-truck sticker sticker-2"></i>
    <i class="bi bi-receipt sticker sticker-3"></i>
    <i class="bi bi-check2-all sticker sticker-4"></i>
    <i class="bi bi-journal-text sticker sticker-5"></i>
</div>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-black text-dark mb-0 shadow-text">PESANAN MASUK</h1>
            <p class="text-muted fw-bold mt-2">Pantau dan kelola pengiriman pesanan pelanggan.</p>
        </div>
        <div class="brand-card p-3 bg-white">
            <h5 class="fw-bold mb-0">{{ $orders->total() }} Total Pesanan</h5>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success brand-card bg-green text-white border-dark p-3 mb-4 fw-bold">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        @forelse($orders as $order)
            <div class="col-12">
                <div class="brand-card shadow-sm hover-lift bg-white">
                    <div class="row g-0">
                        <div class="col-md-3 bg-yellow p-4 border-end border-2 border-dark d-flex flex-column" style="background: var(--fh-yellow); border-top-left-radius: 18px; border-bottom-left-radius: 18px;">
                            <span class="text-dark opacity-75 small fw-bold text-uppercase mb-2">Detail Pesanan</span>
                            <div class="d-flex flex-column gap-2 overflow-auto" style="max-height: 200px;">
                                @foreach($order->orderDetails as $detail)
                                    <div class="bg-white border border-dark border-1 px-3 py-2 rounded-brand shadow-sm d-flex justify-content-between align-items-center">
                                        <span class="fw-black">{{ $detail->qty }}x</span>
                                        <span class="fw-bold small ms-2 text-end">{{ $detail->menu->nama_display }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-9 p-4">
                            <div class="row align-items-center">
                                <div class="col-md-7">
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center gap-3 mb-2">
                                            <h3 class="fw-black text-dark mb-0">#{{ $order->order_id }}</h3>
                                            {!! $order->status_badge !!}
                                            {!! $order->status_pesanan_badge !!}
                                        </div>
                                        <p class="text-muted fw-bold small mb-0"><i class="bi bi-calendar-event me-2"></i>{{ $order->tgl_pesan->format('d M Y, H:i') }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Pelanggan</span>
                                        <h5 class="fw-bold mb-0"><i class="bi bi-person-circle me-2"></i>{{ $order->user->nama ?? 'Pelanggan #' . $order->user_id }}</h5>
                                        <p class="text-muted small mb-0">{{ $order->user->username ?? '' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <span class="text-muted small fw-bold text-uppercase d-block mb-1">Metode</span>
                                                <span class="badge {{ $order->metode_pengantaran == 'ambil_eureka' ? 'bg-warning text-dark' : 'bg-primary text-white' }} border border-dark rounded-pill px-3 py-1 fw-bold">
                                                    {{ $order->metode_pengantaran == 'ambil_eureka' ? 'Ambil di Eureka' : 'Antar Alamat' }}
                                                </span>
                                            </div>
                                            @if($order->metode_pengantaran == 'ambil_eureka')
                                            <div class="col-6">
                                                <span class="text-muted small fw-bold text-uppercase d-block mb-1">Jam Ambil</span>
                                                <span class="fw-bold fs-6"><i class="bi bi-clock me-1"></i>{{ $order->jam_pengambilan }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mb-0">
                                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Lokasi / Alamat</span>
                                        <p class="mb-0 fw-medium small"><i class="bi bi-geo-alt-fill me-2 text-red"></i>{{ $order->alamat_pengiriman }}</p>
                                    </div>
                                </div>
                                <div class="col-md-5 border-start border-2 border-dark border-opacity-10 ps-md-4 mt-4 mt-md-0">
                                    <div class="mb-4">
                                        <span class="text-muted small fw-bold text-uppercase d-block mb-2">Update Status Pengiriman</span>
                                        <div class="d-flex flex-column gap-2">
                                            <form action="{{ route('admin.orders.updateStatus', $order->order_id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status_pesanan" value="perlu dikirim">
                                                <button type="submit" class="brand-btn brand-btn-warning w-100 py-2 fs-6 {{ $order->status_pesanan == 'perlu dikirim' ? 'disabled opacity-50' : '' }}">
                                                    <i class="bi bi-clock me-2"></i>Perlu Dikirim
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.orders.updateStatus', $order->order_id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status_pesanan" value="dalam pengiriman">
                                                <button type="submit" class="brand-btn brand-btn-primary w-100 py-2 fs-6 {{ $order->status_pesanan == 'dalam pengiriman' ? 'disabled opacity-50' : '' }}">
                                                    <i class="bi bi-truck me-2"></i>Dalam Pengiriman
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.orders.updateStatus', $order->order_id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status_pesanan" value="terkirim">
                                                <button type="submit" class="brand-btn brand-btn-success w-100 py-2 fs-6 {{ $order->status_pesanan == 'terkirim' ? 'disabled opacity-50' : '' }}">
                                                    <i class="bi bi-check2-all me-2"></i>Terkirim
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded-4 border border-dark border-2">
                                        <span class="fw-bold text-muted small">TOTAL BAYAR</span>
                                        <span class="fw-black text-danger fs-4">{{ $order->formatted_total }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="brand-card p-5 bg-white d-inline-block">
                    <i class="bi bi-inbox display-1 text-muted opacity-25 d-block mb-4"></i>
                    <h4 class="fw-bold text-muted">Belum ada pesanan masuk.</h4>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center brand-pagination">
        {{ $orders->onEachSide(1)->links() }}
    </div>
</div>

<style>
    .bg-green { background-color: var(--fh-green) !important; }
    .text-red { color: var(--fh-red); }
</style>
@endsection
