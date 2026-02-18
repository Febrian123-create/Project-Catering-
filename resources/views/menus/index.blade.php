@extends('layouts.app')

@section('title', 'Menu')

@section('content')
<div class="sticker-container">
    <i class="bi bi-egg sticker sticker-1"></i>
    <i class="bi bi-cup-hot sticker sticker-2"></i>
    <i class="bi bi-star sticker sticker-3"></i>
    <i class="bi bi-heart sticker sticker-4"></i>
    <i class="bi bi-brightness-high sticker sticker-5"></i>
</div>

<div class="container py-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <h2 class="section-title mb-1">Menu Tersedia</h2>
            <p class="text-muted mb-0 fw-bold">Pilih hidangan lezat untuk tanggal yang diinginkan</p>
        </div>
        <form class="d-flex gap-2 align-items-center" method="GET">
            <input type="date" name="date" class="form-control rounded-pill border-2 border-dark px-4" value="{{ request('date') }}" style="height: 45px;">
            <button type="submit" class="brand-btn brand-btn-primary">Filter</button>
            @if(request('date'))
                <a href="{{ route('menus.index') }}" class="brand-btn brand-btn-warning text-decoration-none">Reset</a>
            @endif
        </form>
    </div>

    <div class="row g-4">
        @php
            $card_styles = ['brand-card-blue', 'brand-card-green', 'brand-card-purple', ''];
        @endphp
        @forelse($menus as $index => $menu)
            <div class="col-md-6 col-lg-4">
                <div class="brand-card {{ $card_styles[$index % 4] }} h-100">
                    @if($menu->foto_display)
                        <img src="{{ asset('storage/' . $menu->foto_display) }}" class="card-img-top border-bottom border-2 border-dark" 
                            alt="{{ $menu->nama_display }}" style="height: 220px; object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center border-bottom border-2 border-dark" 
                            style="height: 220px;">
                            <i class="bi bi-image display-4 text-white opacity-50"></i>
                        </div>
                    @endif
                    
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-warning text-dark border border-dark px-3 py-2 rounded-pill fw-bold shadow-sm">
                                <i class="bi bi-calendar2-heart me-1"></i>
                                {{ $menu->tgl_tersedia->format('d M Y') }}
                            </span>
                            @if($menu->isPaket())
                                <span class="badge bg-primary text-white border border-dark px-3 py-2 rounded-pill fw-bold shadow-sm">
                                    <i class="bi bi-collection me-1"></i>PAKET
                                </span>
                            @endif
                        </div>
                        
                        <h4 class="fw-bold text-dark mb-2">{{ $menu->nama_display }}</h4>
                        <p class="text-muted small fw-bold mb-4 line-clamp-2">{{ Str::limit($menu->deskripsi_display, 100) }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <div>
                                <small class="text-muted fw-bold text-uppercase">Harga</small>
                                <div class="h4 fw-bold text-danger mb-0">
                                    {{ $menu->formatted_harga }}
                                </div>
                            </div>
                            <a href="{{ route('menus.show', $menu->menu_id) }}" class="brand-btn brand-btn-primary text-decoration-none">
                                <i class="bi bi-cart-plus me-1"></i> Pesan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center">
                <div class="mb-4">
                    <i class="bi bi-calendar-x display-1 text-muted opacity-25"></i>
                </div>
                <h4 class="fw-bold text-dark fs-3">Tidak Ada Menu Tersedia</h4>
                <p class="text-muted mx-auto fw-bold" style="max-width: 400px;">Maaf, sepertinya tidak ada menu untuk tanggal ini. Silakan coba pilih tanggal lain!</p>
                <a href="{{ route('menus.index') }}" class="brand-btn brand-btn-primary text-white text-decoration-none mt-3">
                    Lihat Semua Menu
                </a>
            </div>
        @endforelse
    </div>

    @if($menus->hasPages())
        <div class="mt-5 d-flex justify-content-center">
            {{ $menus->links() }}
        </div>
    @endif
</div>
@endsection
