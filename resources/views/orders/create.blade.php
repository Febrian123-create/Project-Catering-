@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-credit-card me-2"></i>Checkout</h2>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Detail Pengiriman</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Alamat Pengiriman <span class="text-danger">*</span></label>
                            <textarea name="alamat_pengiriman" class="form-control @error('alamat_pengiriman') is-invalid @enderror" 
                                rows="3" placeholder="Masukkan alamat lengkap" required>{{ old('alamat_pengiriman', Auth::user()->alamat_default) }}</textarea>
                            @error('alamat_pengiriman')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan <span class="text-muted">(opsional)</span></label>
                            <textarea name="notes" class="form-control" rows="2" 
                                placeholder="Catatan khusus untuk pesanan Anda">{{ old('notes') }}</textarea>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pesanan Anda</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($keranjangs as $item)
                                <tr>
                                    <td>{{ $item->menu->product->nama }}</td>
                                    <td class="text-center">{{ $item->menu->tgl_tersedia->format('d M') }}</td>
                                    <td class="text-center">{{ $item->qty }}</td>
                                    <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 100px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Total Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-4">
                        <span class="h5 mb-0">Total:</span>
                        <span class="h4 mb-0 text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <button type="submit" form="checkoutForm" class="btn btn-primary w-100 btn-lg">
                        <i class="bi bi-check-circle me-2"></i>Buat Pesanan
                    </button>
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
