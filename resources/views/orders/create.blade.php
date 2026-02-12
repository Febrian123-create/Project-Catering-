@extends('layouts.app')

@section('title', 'Checkout')

@push('styles')
<style>
    .checkout-card {
        border: none;
        border-radius: 24px;
        background: white;
    }
    .form-label {
        font-weight: 700;
        color: var(--text-black);
        margin-bottom: 10px;
    }
    .form-control {
        border-radius: 12px;
        border: 2px solid #eee;
        padding: 12px 18px;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: var(--fh-blue);
        box-shadow: none;
    }
    .item-row {
        padding: 15px 0;
        border-bottom: 1px solid #f8f9fa;
    }
    .item-row:last-child {
        border-bottom: none;
    }
    .summary-box {
        background: #f8f9fa;
        border-radius: 20px;
        padding: 20px;
    }
    .btn-order {
        background: var(--fh-red);
        border: none;
        border-radius: 15px;
        padding: 18px;
        font-weight: 800;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }
    .btn-order:hover {
        background: #e5564a;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(250, 98, 85, 0.2);
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="mb-5">
        <h2 class="fw-bold"><i class="bi bi-credit-card me-3 text-primary"></i>Checkout</h2>
        <p class="text-muted">Konfirmasi pesanan Anda dan lengkapi detail pengiriman.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card checkout-card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Informasi Pengiriman</h5>
                    <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="alamat_pengiriman" class="form-control @error('alamat_pengiriman') is-invalid @enderror" 
                                rows="3" placeholder="Masukkan alamat pengaliran detail (Jalan, No Rumah, RT/RW)" required>{{ old('alamat_pengiriman', Auth::user()->alamat_default) }}</textarea>
                            @error('alamat_pengiriman')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Catatan Tambahan</label>
                            <textarea name="notes" class="form-control" rows="2" 
                                placeholder="Contoh: Titip di satpam, jangan pedas, dll.">{{ old('notes') }}</textarea>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card checkout-card shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Rincian Item</h5>
                    <div class="px-2">
                        @foreach($keranjangs as $item)
                            <div class="item-row">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold mb-0 text-black">{{ $item->menu->product->nama }}</h6>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            {{ $item->menu->tgl_tersedia->format('d M Y') }}
                                            <span class="ms-2">•</span> 
                                            <span class="ms-2">{{ $item->qty }} Porsi</span>
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card checkout-card shadow-sm sticky-top" style="top: 100px; border-top: 5px solid var(--fh-blue);">
                <div class="card-body p-4 text-black">
                    <h5 class="fw-bold mb-4">Total Pembayaran</h5>
                    
                    <div class="summary-box mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal ({{ $keranjangs->sum('qty') }} item)</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Biaya Pengiriman</span>
                            <span class="text-success fw-bold">Gratis</span>
                        </div>
                        <hr class="my-3 opacity-10">
                        <div class="d-flex justify-content-between">
                            <span class="h5 fw-bold mb-0">Total Tagihan</span>
                            <span class="h4 fw-bold text-primary mb-0">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" form="checkoutForm" class="btn btn-order text-white w-100 mb-3">
                        Konfirmasi Pesanan <i class="bi bi-check2-circle ms-2"></i>
                    </button>
                    
                    <div class="text-center">
                        <a href="{{ route('cart.index') }}" class="btn btn-link text-muted text-decoration-none small">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 px-3 text-center">
                <p class="small text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    Pesanan Anda akan dikonfirmasi oleh admin segera setelah pembayaran diverifikasi.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
