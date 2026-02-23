@extends('layouts.app')

@section('title', 'Detail Menu - ' . $menu->nama_display)

@section('content')
<div class="sticker-container">
    <i class="bi bi-egg sticker sticker-1"></i>
    <i class="bi bi-cup-hot sticker sticker-2"></i>
    <i class="bi bi-star sticker sticker-3"></i>
    <i class="bi bi-heart sticker sticker-4"></i>
    <i class="bi bi-brightness-high sticker sticker-5"></i>
</div>

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-5">
        <ol class="breadcrumb brand-breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-dark">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('menus.index') }}" class="text-decoration-none text-dark">Menu</a></li>
            <li class="breadcrumb-item active fw-bold text-danger">Detail</li>
        </ol>
    </nav>

    <div class="row g-5">
        <div class="col-lg-8">
            <div class="brand-card shadow-lg mb-4">
                @if($menu->foto_display)
                    <div class="border-bottom border-2 border-dark overflow-hidden">
                        <img src="{{ asset('storage/' . $menu->foto_display) }}" class="card-img-top" 
                            alt="{{ $menu->nama_display }}" style="max-height: 500px; width: 100%; object-fit: cover;">
                    </div>
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center border-bottom border-2 border-dark" style="height: 300px;">
                        <i class="bi bi-image display-1 text-muted opacity-25"></i>
                    </div>
                @endif
                
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex gap-3 mb-4 flex-wrap">
                        <span class="badge bg-warning text-dark border border-dark px-3 py-2 rounded-pill fw-bold shadow-sm">
                            <i class="bi bi-calendar2-heart me-1"></i>
                            Tersedia: {{ $menu->tgl_tersedia->format('l, d F Y') }}
                        </span>
                        @if($menu->isPaket())
                            <span class="badge bg-primary text-white border border-dark px-3 py-2 rounded-pill fw-bold shadow-sm">
                                <i class="bi bi-collection me-1"></i>Menu Paket
                            </span>
                        @endif
                    </div>

                    <h1 class="fw-black text-dark mb-3 display-5 fw-bold" style="letter-spacing: -2px;">{{ $menu->nama_display }}</h1>
                    <div class="h3 fw-bold text-danger mb-4 shadow-text">
                        {{ $menu->formatted_harga }}
                    </div>
                    
                    <div class="mb-5">
                        <h5 class="fw-bold text-dark border-start border-4 border-warning ps-3 mb-3">DESKRIPSI HIDANGAN</h5>
                        <p class="text-muted fs-5 fw-medium">{{ $menu->deskripsi_display }}</p>
                    </div>

                    {{-- Show included products for paket --}}
                    @if($menu->isPaket() && $menu->products->count() > 0)
                        <div class="mt-5 pt-4 border-top border-2 border-dark border-dashed">
                            <h5 class="fw-bold text-dark mb-4"><i class="bi bi-layout-text-sidebar-reverse me-2"></i>Produk dalam Paket</h5>
                            <div class="row g-3">
                                @foreach($menu->products as $product)
                                    <div class="col-12">
                                        <div class="brand-card bg-white p-3 hover-lift border-1">
                                            <div class="d-flex align-items-center">
                                                @if($product->foto)
                                                    <img src="{{ asset('storage/' . $product->foto) }}" class="rounded-3 border border-dark border-1 shadow-sm me-3" width="70" height="70" style="object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center border border-dark border-1 shadow-sm me-3" style="width: 70px; height: 70px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <h6 class="fw-bold mb-1 text-dark">{{ $product->nama }}</h6>
                                                        <span class="badge bg-danger-subtle text-danger border border-danger small px-2 py-1 rounded-pill">{{ $product->formatted_harga }}</span>
                                                    </div>
                                                    @if($product->deskripsi)
                                                        <p class="text-muted small mb-0">{{ Str::limit($product->deskripsi, 80) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="brand-card bg-white p-4 p-md-5 mb-5 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-dark mb-0">Ulasan Pelanggan <span class="badge bg-dark rounded-circle ms-2">{{ $menu->reviews->count() }}</span></h4>
                </div>
                
                @forelse($menu->reviews as $review)
                    <div class="review-item border-bottom border-1 border-dark border-opacity-10 pb-4 mb-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-purple text-dark border border-dark rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 45px; height: 45px; background: var(--fh-purple);">
                                    {{ substr($review->user->nama ?? 'P', 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $review->user->nama ?? 'Pengguna' }}</div>
                                    <div class="text-warning small">
                                        @for($i = 0; $i < 5; $i++)
                                            <i class="bi bi-star-fill {{ $i < $review->bintang ? '' : 'text-muted opacity-25' }}"></i>
                                        @endfor
                                        <span class="text-dark ms-1 fw-bold">{{ $review->bintang }}.0</span>
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted fw-bold">{{ $review->tgl_review->format('d M Y') }}</small>
                        </div>
                        <p class="mb-0 text-muted ps-5 ms-3">{{ $review->isi_review }}</p>
                    </div>
                @empty
                    <div class="text-center py-4 bg-light rounded-brand border border-dashed border-2 border-dark border-opacity-25">
                        <i class="bi bi-chat-left-dots display-4 text-muted opacity-25 d-block mb-3"></i>
                        <p class="text-muted fw-bold mb-0">Belum ada ulasan untuk menu ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="col-lg-4">
            <div class="brand-card sticky-top shadow-lg overflow-visible" style="top: 100px;">
                <div class="bg-yellow border-bottom border-2 border-dark p-3" style="background: var(--fh-yellow);">
                    <h5 class="fw-black mb-0 text-dark"><i class="bi bi-bag-plus-fill me-2"></i>Pesan Menu</h5>
                </div>
                <div class="card-body p-4">
                    @auth
                        @php
                            $isWeeklyTab = request()->query('from') === 'mingguan';
                        @endphp

                        @if($isWeeklyTab)
                            <div class="alert alert-info border-2 border-dark rounded-4 fw-bold mb-4">
                                <i class="bi bi-info-circle-fill me-2"></i> Anda baru saja melihat menu ini dari paket mingguan. Gunakan tombol di halaman utama untuk memesan seluruh paket mingguan sekaligus.
                            </div>
                        @else
                            <form action="{{ route('cart.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="menu_id" value="{{ $menu->menu_id }}">
                                
                                <div class="mb-4">
                                    <label class="fw-bold text-dark mb-3 d-block">Jumlah Porsi</label>
                                    <div class="qty-selector justify-content-center">
                                        <button type="button" class="qty-btn" id="qtyMinus"><i class="bi bi-dash-lg"></i></button>
                                        <input type="number" name="qty" id="qtyInput" class="qty-input" value="1" min="1" readonly>
                                        <button type="button" class="qty-btn" id="qtyPlus"><i class="bi bi-plus-lg"></i></button>
                                    </div>
                                </div>

                                <div class="p-3 bg-light rounded-4 border-2 border border-dark mb-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-muted small text-uppercase">Subtotal</span>
                                        <strong class="h4 fw-bold text-danger mb-0" id="displayTotal">
                                            {{ $menu->formatted_harga }}
                                        </strong>
                                    </div>
                                </div>

                                <div class="d-grid gap-3">
                                    <button type="submit" name="action" value="buy_now" class="brand-btn brand-btn-primary py-3 fs-5">
                                        Pesan Sekarang
                                    </button>
                                    <button type="submit" name="action" value="add_to_cart" class="brand-btn py-3 bg-white text-dark">
                                        <i class="bi bi-cart-plus me-1"></i>+ Keranjang
                                    </button>
                                </div>
                            </form>
                        @endif
                    @else
                        <div class="alert alert-warning border-2 border-dark rounded-4 fw-bold mb-4">
                            <i class="bi bi-info-circle-fill me-2"></i> Silakan login untuk melakukan pemesanan.
                        </div>
                        <a href="{{ route('login') }}" class="brand-btn brand-btn-primary w-100 text-center text-decoration-none py-3 fs-5">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const qtyInput = document.getElementById('qtyInput');
    const plusBtn = document.getElementById('qtyPlus');
    const minusBtn = document.getElementById('qtyMinus');
    const displayTotal = document.getElementById('displayTotal');
    const basePrice = {{ $menu->isPaket() ? $menu->harga_paket : $menu->product->harga }};

    function formatRupiah(amount) {
        return 'Rp ' + amount.toLocaleString('id-ID');
    }

    if(plusBtn) {
        plusBtn.addEventListener('click', () => {
            qtyInput.value = parseInt(qtyInput.value) + 1;
            displayTotal.textContent = formatRupiah(parseInt(qtyInput.value) * basePrice);
        });
    }

    if(minusBtn) {
        minusBtn.addEventListener('click', () => {
            if (parseInt(qtyInput.value) > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
                displayTotal.textContent = formatRupiah(parseInt(qtyInput.value) * basePrice);
            }
        });
    }
});
</script>
@endpush
@endsection
