@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@push('styles')
<style>
    .cart-card {
        border: none;
        border-radius: 20px;
        background: white;
        transition: all 0.3s ease;
    }
    .cart-item-card {
        border-bottom: 1px solid #eee;
        padding: 20px 0;
    }
    .cart-item-card:last-child {
        border-bottom: none;
    }
    .qty-input {
        max-width: 80px;
        border-radius: 10px;
        border: 2px solid #eee;
        text-align: center;
    }
    .summary-card {
        background: #fff9e6;
        border: none;
        border-radius: 24px;
        padding: 25px;
    }
    .btn-checkout {
        background: var(--fh-red);
        border: none;
        border-radius: 15px;
        padding: 15px;
        font-weight: 700;
        transition: all 0.3s ease;
    }
    .btn-checkout:hover {
        background: #e5564a;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(250, 98, 85, 0.3);
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center mb-5">
        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
            <i class="bi bi-cart3 h3 mb-0 text-primary"></i>
        </div>
        <h2 class="fw-bold mb-0">Keranjang Belanja</h2>
    </div>

    @if($cartItems->count() > 0)
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card cart-card shadow-sm p-4">
                    <div class="d-none d-md-flex text-muted small fw-bold mb-3 px-2">
                        <div style="flex: 2;">MENU</div>
                        <div style="flex: 1;" class="text-center">TANGGAL</div>
                        <div style="flex: 1;" class="text-center">KUANTITAS</div>
                        <div style="flex: 1;" class="text-end">SUBTOTAL</div>
                        <div style="width: 50px;"></div>
                    </div>

                    @foreach($cartItems as $item)
                        <div class="cart-item-card px-2">
                            <div class="row align-items-center g-3">
                                <div class="col-12 col-md-5">
                                    <h6 class="fw-bold mb-1">{{ $item->menu->product->nama }}</h6>
                                    <p class="text-muted small mb-0">{{ $item->menu->product->formatted_harga }} / porsi</p>
                                </div>
                                <div class="col-6 col-md-2 text-md-center">
                                    <span class="badge rounded-pill bg-light text-dark border">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        {{ $item->menu->tgl_tersedia->format('d M') }}
                                    </span>
                                </div>
                                <div class="col-6 col-md-2">
                                    <form action="{{ route('cart.update', $item->menu_id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="qty" class="form-control qty-input mx-auto" 
                                            value="{{ $item->qty }}" min="1"
                                            onchange="this.form.submit()">
                                    </form>
                                </div>
                                <div class="col-6 col-md-2 text-md-end">
                                    <span class="fw-bold text-primary">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="col-6 col-md-1 text-end overflow-visible">
                                    <form action="{{ route('cart.destroy', $item->menu_id) }}" method="POST" 
                                        onsubmit="return confirm('Hapus item ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0">
                                            <i class="bi bi-x-circle h5"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-4 pt-3 d-flex justify-content-between">
                        <a href="{{ route('menus.index') }}" class="btn btn-link text-decoration-none text-muted p-0">
                            <i class="bi bi-arrow-left me-2"></i>Lanjut Belanja
                        </a>
                        <form action="{{ route('cart.clear') }}" method="POST" 
                            onsubmit="return confirm('Kosongkan keranjang?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger text-decoration-none p-0">
                                <i class="bi bi-trash me-2"></i>Kosongkan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card summary-card shadow-sm sticky-top" style="top: 100px;">
                    <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total Porsi</span>
                        <span class="fw-bold">{{ $cartItems->sum('qty') }} porsi</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-4">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <div class="border-top border-dark border-opacity-10 pt-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 fw-bold mb-0">Total Tagihan</span>
                            <span class="h4 fw-bold text-primary mb-0">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('orders.create') }}" class="btn btn-checkout text-white w-100 mb-3">
                        Pesan Sekarang <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    
                    <p class="small text-muted text-center mb-0">
                        <i class="bi bi-shield-check me-1"></i> Pembayaran Aman
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="card cart-card shadow-sm py-5 px-4 text-center">
            <div class="mb-4">
                <i class="bi bi-cart-x display-1 text-muted opacity-25"></i>
            </div>
            <h4 class="fw-bold">Keranjang Anda Kosong</h4>
            <p class="text-muted mb-4">Sepertinya Anda belum memilih menu lezat untuk hari ini.</p>
            <div>
                <a href="{{ route('menus.index') }}" class="btn btn-primary px-5 py-3 rounded-pill fw-bold">
                    Jelajahi Menu
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
