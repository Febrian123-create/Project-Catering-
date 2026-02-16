@extends('layouts.app')

@section('title', 'Welcome to Dosinyam')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/custom-home.css') }}">
@endpush

@section('content')
    {{-- Hero Section --}}
    <section class="hero-section text-center position-relative pb-5" style="overflow: hidden;">
        {{-- Decorative Stars --}}
        <svg class="sticker star-1 floating" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </svg>
        <svg class="sticker star-2 floating-slower" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </svg>

        <div class="container pt-5">
            <div class="hero-circle-container mb-5">
                <div class="curved-text">
                    <svg viewBox="0 0 600 150">
                        <path id="curve" d="M30,150 Q300,50 570,150" fill="transparent"/>
                        <text width="600">
                            <textPath xlink:href="#curve" startOffset="50%" text-anchor="middle" fill="#5c4033" style="font-size: 20px; font-weight: bold; letter-spacing: 1px;">
                                We believe that every bite is a moment of happiness
                            </textPath>
                        </text>
                    </svg>
                </div>
                
                {{-- Main Hero Image --}}
                <img src="{{ asset('img/bento.png') }}" class="main-hero-img floating" alt="Dosinyam Bento">

                {{-- Orbiting Images (Placeholders or same image for demo) --}}
                <img src="{{ asset('img/bento-landscape.png') }}" class="orbit-item orbit-1 floating-slower" alt="Side Dish">
                <img src="{{ asset('img/bento.png') }}" class="orbit-item orbit-2 floating" alt="Small Bento">
                <img src="{{ asset('img/bento-landscape.png') }}" class="orbit-item orbit-3 floating-slower" alt="Snack">
            </div>

            <h1 class="display-4 fw-bold mb-3" style="color: var(--primary-orange); letter-spacing: -1px;">
                Taste the Joy of <span style="color: var(--text-brown);">Dosinyam</span>
            </h1>
            <p class="lead mb-4" style="color: var(--text-brown); max-width: 600px; margin: 0 auto;">
                Authentic Korean packed meals made with love. Perfect for your daily lunch or special events!
            </p>
            <a href="{{ route('menus.index') }}" class="btn-signin d-inline-block" style="max-width: 250px; text-decoration: none;">
                Order Now
            </a>
        </div>
    </section>

    {{-- Special Events / Weekly Menu Carousel --}}
    <section class="py-5 position-relative">
        <div class="container">
            <div class="text-center mb-4">
                <span class="badge rounded-pill bg-white text-dark border border-2 px-3 py-2 fs-6 mb-2" style="border-color: var(--primary-orange) !important;">
                    Menu Minggu Ini
                </span>
                <h2 class="fw-bold" style="color: var(--text-brown);">Weekly Specials</h2>
            </div>

            {{-- Horizontal Scroll Container --}}
            <div class="scroll-container ps-4">
                @forelse($menus as $menu)
                    <div class="menu-card">
                        @if($menu->product->foto)
                            <img src="{{ asset('storage/' . $menu->product->foto) }}" class="menu-img" alt="{{ $menu->product->nama }}">
                        @else
                            <div class="menu-img bg-light d-flex align-items-center justify-content-center">
                                <i class="bi bi-egg-fried fs-1 text-muted"></i>
                            </div>
                        @endif
                        <div class="p-3 text-center">
                            <small class="text-muted d-block mb-1">{{ $menu->tgl_tersedia->format('D, d M') }}</small>
                            <h6 class="fw-bold text-truncate mb-2" style="color: var(--text-brown);">{{ $menu->product->nama }}</h6>
                            <a href="{{ route('menus.show', $menu->menu_id) }}" class="btn btn-sm btn-outline-danger w-100 rounded-pill">
                                Lihat
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center w-100 p-5">
                        <p class="text-muted">Belum ada menu minggu ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Cloud Separator Top --}}
    <div class="cloud-separator-top"></div>

    {{-- What is Dosinyam Section --}}
    <section class="cloud-content text-center position-relative">
        <div class="container py-5">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-3 d-none d-md-block">
                    {{-- Cute Illustration Left --}}
                    <img src="{{ asset('img/bento.png') }}" class="img-fluid floating" style="max-width: 150px; transform: rotate(-10deg);" alt="Cute Bento">
                </div>
                <div class="col-md-6">
                    <span class="badge bg-white text-dark border border-1 px-3 py-1 mb-3 rounded-pill">Loyalty Program</span>
                    <h2 class="fw-bold mb-3" style="font-family: 'Quicksand', sans-serif;">Dear our guests!</h2>
                    <p class="mb-4" style="color: var(--text-brown);">
                        We believe that each of you is not just a visitor, but a true friend! That is why we express our gratitude for your support and trust. We have created a special loyalty program that will make your time at our catering even more enjoyable and memorable.
                    </p>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 bg-white rounded-3 shadow-sm h-100">
                                <h6 class="fw-bold" style="color: var(--primary-orange);">STEP 1</h6>
                                <p class="small text-muted mb-0">Order your delicious dosirak</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-white rounded-3 shadow-sm h-100">
                                <h6 class="fw-bold" style="color: var(--primary-orange);">STEP 2</h6>
                                <p class="small text-muted mb-0">Earn points & get rewards!</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 d-none d-md-block">
                    {{-- Cute Illustration Right --}}
                    <img src="{{ asset('img/bento-landscape.png') }}" class="img-fluid floating-slower" style="max-width: 150px; transform: rotate(10deg);" alt="Cute Snack">
                </div>
            </div>
        </div>
    </section>

    {{-- Cloud Separator Bottom --}}
    <div class="cloud-separator-bottom"></div>

    {{-- Origin Story Section --}}
    <section class="py-5">
        <div class="container text-center">
            <h1 class="display-3 fw-bold mb-5" style="color: #fff; -webkit-text-stroke: 2px var(--text-brown); font-family: 'Quicksand', sans-serif;">GALLERY</h1>
            
            {{-- Gallery Grid --}}
            <div class="gallery-grid mb-5">
               @foreach($products->take(5) as $product)
                 <div class="gallery-item">
                    @if($product->foto)
                         <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama }}">
                    @else
                         <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                             <i class="bi bi-image text-muted fs-1"></i>
                         </div>
                    @endif
                 </div>
               @endforeach
               {{-- Fill remaining slots if few products --}}
               @for($i = 0; $i < max(0, 5 - $products->count()); $i++)
                   <div class="gallery-item">
                       <img src="https://placehold.co/300x300/ffefd5/fa6255?text=Yummy" alt="Placeholder">
                   </div>
               @endfor
            </div>

            {{-- Origin Story Box --}}
            <div class="origin-story-box mt-5">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <span class="badge bg-warning text-dark rounded-pill mb-3">Origin Story</span>
                        <h2 class="dosi-text mb-3">DOSI + NYAM</h2>
                        <p class="fs-5" style="color: var(--text-brown);">
                            <strong>"DOSI"</strong> comes from <em>Dosirak </em> / 도시락 which means "lunchbox" in Korean. We are here to bring the warmth of a home-cooked meal into your busy daily routine.<br>
                            <strong>"NYAM"</strong> Nyam! is the sound of pure satisfaction every time you enjoy a delicious, comforting meal that hits the spot.
                        </p>
                        <hr class="my-4" style="border-top: 2px dashed var(--primary-orange);">
                        <p class="mb-0">
                            Combined, <strong>Dosinyam</strong> represent our commitment to providing you with a lunchbox experience that keeps you coming back for more!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Reviews Section --}}
    <section class="py-5" style="background-color: #fff9f0;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold" style="font-family: 'Quicksand', sans-serif;">Reviews</h2>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="review-card">
                        <h5 class="fw-bold mb-1">Alex</h5>
                        <div class="text-warning mb-2">★★★★★</div>
                        <p class="small mb-0">"The best dosirak in town! Truly authentic taste."</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="review-card">
                        <h5 class="fw-bold mb-1">Mary</h5>
                        <div class="text-warning mb-2">★★★★★</div>
                        <p class="small mb-0">"Cute packaging and delicious food. Highly recommended!"</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="review-card">
                        <h5 class="fw-bold mb-1">Rick</h5>
                        <div class="text-warning mb-2">★★★★★</div>
                        <p class="small mb-0">"My weekly lunch solution. Healthy and tasty."</p>
                    </div>
                </div>
                 <div class="col-md-3">
                    <div class="review-card">
                        <h5 class="fw-bold mb-1">Morty</h5>
                        <div class="text-warning mb-2">★★★★★</div>
                        <p class="small mb-0">"Aw jeez, this is really good!"</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
