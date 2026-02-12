@extends('layouts.app')

@section('title', 'Welcome to Dosinyam')

@section('content')
    <section class="hero-section text-center py-5" style="background-color: var(--bg-krem);">
        <div class="container">
            <h1 class="display-3 fw-bold mb-3" style="color: var(--primary-orange); letter-spacing: -2px;">
                Annyeong! Welcome to <span style="color: var(--text-brown);">Dosinyam</span>
            </h1>
            <p class="lead mb-4" style="color: var(--text-brown); font-weight: 500;">
                Bringing joy to your lunchbox with authentic Korean vibes. Nyam!
            </p>
            <a href="{{ route('menus.index') }}" class="btn-signin" style="max-width: 250px; text-decoration: none; display: inline-block;">
                Explore Today's Menu
            </a>
        </div>
    </section>

    <section class="py-5 bg-white" style="border-radius: 50px 50px 0 0;">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-md-6">
                    <div class="p-4">
                        <h3 style="color: var(--primary-orange); font-weight: 800;">Dosi (Dosirak)</h3>
                        <p class="text-muted">Inspired by the warmth of home-packed Korean meals, crafted with love and high-quality ingredients.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-4">
                        <h3 style="color: var(--primary-orange); font-weight: 800;">Nyam!</h3>
                        <p class="text-muted">A promise of flavor. We ensure every bite makes you say "Nyam!" with pure happiness.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5" style="background-color: #fff;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold" style="color: var(--primary-orange);">Daily Specials</h2>
                <p class="text-muted">Pick your favorite box for your special day</p>
            </div>

            <div class="row g-4">
                @forelse($menus as $menu)
                    <div class="col-md-4">
                        <div class="card h-100 slide-card" style="border: 2px solid var(--primary-orange); border-radius: 25px;">
                            @if($menu->product->foto)
                                <img src="{{ asset('storage/' . $menu->product->foto) }}" class="card-img-top"
                                     alt="{{ $menu->product->nama }}" style="height: 250px; object-fit: cover; border-radius: 22px 22px 0 0;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                                    <i class="bi bi-egg-fried display-4 text-muted"></i>
                                </div>
                            @endif
                            <div class="card-body text-center">
                            <span class="badge mb-2" style="background-color: var(--bg-krem); color: var(--primary-orange); border: 1px solid var(--primary-orange);">
                                <i class="bi bi-calendar-check me-1"></i>
                                {{ $menu->tgl_tersedia->format('d M Y') }}
                            </span>
                                <h5 class="card-title fw-bold" style="color: var(--text-brown);">{{ $menu->product->nama }}</h5>
                                <p class="text-muted small">{{ Str::limit($menu->product->deskripsi, 60) }}</p>

                                <div class="d-grid mt-3">
                                    <a href="{{ route('menus.show', $menu->menu_id) }}" class="btn-signin" style="padding: 10px; font-size: 16px; box-shadow: 0 4px 0 #d9534f;">
                                        {{ $menu->product->formatted_harga }} - Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </section>
@endsection
