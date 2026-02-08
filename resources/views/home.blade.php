@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<section class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Layanan Catering Terbaik</h1>
        <p class="lead mb-4">Nikmati hidangan lezat untuk setiap acara spesial Anda</p>
        <a href="{{ route('menus.index') }}" class="btn btn-light btn-lg px-5">
            <i class="bi bi-menu-button-wide me-2"></i>Lihat Menu
        </a>
    </div>
</section>

<!-- Menu Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Menu Tersedia</h2>
            <p class="text-muted">Pilih paket menu untuk tanggal tertentu</p>
        </div>

        <div class="row g-4">
            @forelse($menus as $menu)
                <div class="col-md-4">
                    <div class="card h-100">
                        @if($menu->product->foto)
                            <img src="{{ asset('storage/' . $menu->product->foto) }}" class="card-img-top" 
                                alt="{{ $menu->product->nama }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                style="height: 200px;">
                                <i class="bi bi-image display-4"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-primary">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $menu->tgl_tersedia->format('d M Y') }}
                                </span>
                            </div>
                            
                            <h5 class="card-title">{{ $menu->product->nama }}</h5>
                            <p class="text-muted small mb-3">{{ Str::limit($menu->product->deskripsi, 80) }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 text-primary mb-0">
                                    {{ $menu->product->formatted_harga }}
                                </span>
                                <a href="{{ route('menus.show', $menu->menu_id) }}" class="btn btn-outline-primary btn-sm">
                                    Detail <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <p class="text-muted">Belum ada menu tersedia</p>
                </div>
            @endforelse
        </div>

        @if($menus->count() > 0)
            <div class="text-center mt-4">
                <a href="{{ route('menus.index') }}" class="btn btn-primary">
                    Lihat Semua Menu <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Reviews Section -->
    {{-- Disabled for now as Reviews are per menu and not easily fetched globally without a specific Controller logic --}}
    {{-- Can be added later with $recent_reviews variable from HomeController --}}
@endsection
