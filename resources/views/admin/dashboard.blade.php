@extends('layouts.app')

@section('title', 'Admin Dashboard - Dosinyam')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/custom-home.css') }}">
    <style>
        .stat-highlight-box {
            background: white;
            border-radius: 30px;
            padding: 2rem;
            box-shadow: 8px 8px 0px var(--accent-green);
            border: 2px solid var(--text-brown);
            margin-bottom: 40px;
        }
        .order-badge-today {
            background: var(--primary-orange);
            color: white;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
    {{-- Hero Section --}}
    <section class="hero-section text-center position-relative pb-5" style="overflow: hidden; background-color: var(--bg-krem);">
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
                                Manage your Dosinyam kitchen with joy!
                            </textPath>
                        </text>
                    </svg>
                </div>
                
                {{-- Main Hero Image --}}
                <img src="{{ asset('img/bento.png') }}" class="main-hero-img floating" alt="Dosinyam Admin">

                {{-- Orbiting Images --}}
                <img src="{{ asset('img/bento-landscape.png') }}" class="orbit-item orbit-1 floating-slower" alt="Side Dish">
                <img src="{{ asset('img/bento.png') }}" class="orbit-item orbit-2 floating" alt="Small Bento">
                <img src="{{ asset('img/bento-landscape.png') }}" class="orbit-item orbit-3 floating-slower" alt="Snack">
            </div>

            <h1 class="display-4 fw-bold mb-3" style="color: var(--primary-orange); letter-spacing: -1px;">
                Admin <span style="color: var(--text-brown);">Dashboard</span>
            </h1>
            
            <div class="d-flex justify-content-center gap-3 flex-wrap mb-4">
                <a href="{{ route('admin.menus.create') }}" class="btn-signin d-inline-block" style="max-width: 250px; text-decoration: none;">+ Buat Menu</a>
                <a href="{{ route('admin.products.create') }}" class="btn btn-outline-danger rounded-pill px-4 fw-bold">Tambah Produk</a>
            </div>
        </div>
    </section>


    {{-- Cloud Separator Top --}}
    <div class="cloud-separator-top"></div>

    {{-- Product Catalog Slider --}}
    <section class="cloud-content py-5 position-relative">
        <div class="container">
            <div class="text-center mb-4">
                <span class="badge bg-white text-dark border border-1 px-3 py-1 mb-3 rounded-pill">Product Management</span>
                <h2 class="fw-bold" style="color: var(--text-brown);">Daftar Menu Produk</h2>
            </div>

            <div class="scroll-container ps-4">
                @forelse($products as $product)
                    <div class="menu-card" style="box-shadow: 8px 8px 0px var(--accent-yellow); border: 2px solid var(--text-brown);">
                        @if($product->foto)
                            <img src="{{ asset('storage/' . $product->foto) }}" class="menu-img" alt="{{ $product->nama }}">
                        @else
                            <div class="menu-img bg-light d-flex align-items-center justify-content-center">
                                <i class="bi bi-egg-fried fs-1 text-muted"></i>
                            </div>
                        @endif
                        <div class="p-3 text-center">
                            <h6 class="fw-bold text-truncate mb-2" style="color: var(--text-brown);">{{ $product->nama }}</h6>
                            <p class="small text-muted mb-3">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                            <a href="{{ route('admin.products.edit', $product->product_id) }}" class="btn btn-sm btn-outline-danger w-100 rounded-pill fw-bold">
                                Edit Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center w-100 p-5">
                        <p class="text-muted">Belum ada produk di katalog.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Cloud Separator Bottom --}}
    <div class="cloud-separator-bottom"></div>

    {{-- Recent Orders Section --}}
    <section class="py-5">
        <div class="container">
            <h1 class="display-3 fw-bold mb-5 text-center" style="color: #fff; -webkit-text-stroke: 2px var(--text-brown); font-family: 'Quicksand', sans-serif;">ORDERS</h1>
            
            <div class="card border-0 shadow-sm" style="border-radius: 30px; border: 2px solid var(--text-brown) !important; box-shadow: 10px 10px 0px var(--accent-blue) !important;">
                <div class="card-body p-4">
                    @if($recent_orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Pembeli</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th class="text-center">Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_orders as $order)
                                        <tr>
                                            <td><span class="fw-bold">{{ $order->order_id }}</span></td>
                                            <td>{{ $order->user->nama ?? 'Guest' }}</td>
                                            <td>{{ $order->tgl_pesan->format('d M Y') }}</td>
                                            <td>{{ $order->formatted_total }}</td>
                                            <td class="text-center">{!! $order->status_badge !!}</td>
                                            <td>
                                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-link text-danger fw-bold">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted my-4">Belum ada pesanan terbaru.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Reviews Section --}}
    <section class="py-5" style="background-color: #fff9f0;">
        <div class="container text-center">
            <h2 class="fw-bold mb-5" style="font-family: 'Quicksand', sans-serif;">Review Pelanggan (Random)</h2>
            <div class="row">
                @forelse($reviews as $review)
                    <div class="col-md-3">
                        <div class="review-card h-100 bg-white">
                            <h5 class="fw-bold mb-1">{{ $review->user->name ?? 'Guest' }}</h5>
                            <div class="text-warning mb-2">{{ $review->stars }}</div>
                            <p class="small mb-0">"{{ $review->isi_review }}"</p>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">Belum ada review dari pelanggan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
