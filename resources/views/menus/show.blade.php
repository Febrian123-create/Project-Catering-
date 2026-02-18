@extends('layouts.app')

@section('title', 'Detail Menu')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('menus.index') }}">Menu</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                @if($menu->foto_display)
                    <img src="{{ asset('storage/' . $menu->foto_display) }}" class="card-img-top" 
                        alt="{{ $menu->nama_display }}" style="max-height: 400px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <div class="d-flex gap-3 mb-3 flex-wrap">
                        <span class="badge bg-primary fs-6">
                            <i class="bi bi-calendar me-1"></i>
                            Tersedia: {{ $menu->tgl_tersedia->format('l, d F Y') }}
                        </span>
                        @if($menu->isPaket())
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-collection me-1"></i>Menu Paket
                            </span>
                        @endif
                    </div>

                    <h2 class="card-title fw-bold mb-3">{{ $menu->nama_display }}</h2>
                    <h4 class="text-primary mb-4">{{ $menu->formatted_harga }}</h4>
                    
                    <h5>Deskripsi</h5>
                    <p class="text-muted">{{ $menu->deskripsi_display }}</p>

                    {{-- Show included products for paket --}}
                    @if($menu->isPaket() && $menu->products->count() > 0)
                        <hr>
                        <h5 class="mb-3"><i class="bi bi-collection me-2"></i>Produk dalam Paket</h5>
                        <div class="list-group">
                            @foreach($menu->products as $product)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        @if($product->foto)
                                            <img src="{{ asset('storage/' . $product->foto) }}" class="rounded me-3" width="45" height="45" style="object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <span class="fw-bold">{{ $product->nama }}</span>
                                            @if($product->deskripsi)
                                                <br><small class="text-muted">{{ Str::limit($product->deskripsi, 60) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold">{{ $product->formatted_harga }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Ulasan Pelanggan ({{ $menu->reviews->count() }})</h5>
                    
                    @forelse($menu->reviews as $review)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <span class="fw-bold">{{ $review->user->nama ?? 'Pengguna' }}</span>
                                    <span class="text-warning ms-2 me-1">{{ $review->stars }}</span>
                                    <small class="text-muted">{{ $review->bintang }}.0</small>
                                </div>
                                <small class="text-muted">{{ $review->tgl_review->format('d M Y') }}</small>
                            </div>
                            <p class="mb-0 text-muted">{{ $review->isi_review }}</p>
                        </div>
                    @empty
                        <p class="text-muted text-center py-3">Belum ada ulasan untuk menu ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 100px;">
                <div class="card-body">
                    <h5 class="card-title mb-3">Pesan Menu Ini</h5>
                    
                    @auth
                        <form action="{{ route('cart.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="menu_id" value="{{ $menu->menu_id }}">
                            
                            <div class="mb-3">
                                <label class="form-label">Jumlah Porsi</label>
                                <input type="number" name="qty" class="form-control" 
                                    value="1" min="1">
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>Harga {{ $menu->isPaket() ? 'Paket' : 'Satuan' }}:</span>
                                    <strong>{{ $menu->formatted_harga }}</strong>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" name="action" value="add_to_cart" class="btn btn-primary btn-lg">
                                    <i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang
                                </button>
                                <button type="submit" name="action" value="buy_now" class="btn btn-success btn-lg">
                                    <i class="bi bi-bag-check me-2"></i>Beli Langsung
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle me-2"></i>
                            Silakan login untuk memesan
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-primary w-100">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
