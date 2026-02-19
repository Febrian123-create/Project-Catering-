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
    <div class="mb-5">
        <h2 class="section-title mb-1">Menu Tersedia</h2>
        <p class="text-muted mb-4 fw-bold">Pilih hidangan lezat untuk momen spesial Anda</p>

        <!-- Tab Navigation -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
            <div class="nav-tabs-brand d-flex gap-3">
                <a href="{{ route('menus.index', ['tab' => 'harian']) }}" 
                   class="brand-tab {{ $tab === 'harian' ? 'active' : '' }} text-decoration-none">
                    <i class="bi bi-calendar-event me-2"></i>Menu Harian
                </a>
                <a href="{{ route('menus.index', ['tab' => 'mingguan']) }}" 
                   class="brand-tab {{ $tab === 'mingguan' ? 'active' : '' }} text-decoration-none">
                    <i class="bi bi-calendar-range me-2"></i>Menu Mingguan
                </a>
            </div>

            @if($tab === 'harian')
            <form class="d-flex gap-2 align-items-center" method="GET">
                <input type="hidden" name="tab" value="harian">
                <input type="date" name="date" class="form-control rounded-pill border-2 border-dark px-4 shadow-sm" 
                       value="{{ request('date', now()->toDateString()) }}" style="height: 45px;">
                <button type="submit" class="brand-btn brand-btn-primary">Filter</button>
                @if(request('date'))
                    <a href="{{ route('menus.index', ['tab' => 'harian']) }}" class="brand-btn brand-btn-warning text-decoration-none">Reset</a>
                @endif
            </form>
            @endif
        </div>
    </div>

    <div class="row g-4">
        @php
            $card_styles = ['brand-card-blue', 'brand-card-green', 'brand-card-purple', ''];
        @endphp
        @forelse($menus as $index => $menu)
            <div class="col-md-6 col-lg-4">
                <div class="brand-card {{ $card_styles[$index % 4] }} h-100 menu-detail-trigger"
                    data-id="{{ $menu->menu_id }}"
                    data-nama="{{ $menu->nama_display }}"
                    data-deskripsi="{{ $menu->deskripsi_display }}"
                    data-harga="{{ $menu->formatted_harga }}"
                    data-foto="{{ $menu->foto_display ? asset('storage/' . $menu->foto_display) : '' }}"
                    data-tanggal="{{ $menu->tgl_tersedia->format('d M Y') }}"
                    data-url="{{ route('menus.show', $menu->menu_id) }}">
                    
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
                            <a href="{{ route('menus.show', $menu->menu_id) }}" class="brand-btn brand-btn-primary text-decoration-none" onclick="event.stopPropagation();">
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

<!-- Menu Detail Modal -->
<div class="modal fade" id="menuDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content brand-modal-content">
            <form action="{{ route('cart.store') }}" method="POST" id="modalCartForm">
                @csrf
                <input type="hidden" name="menu_id" id="modalMenuId">
                
                <div class="brand-modal-header d-flex justify-content-between align-items-center">
                    <h4 class="brand-modal-title mb-0" id="modalMenuName">Nama Menu</h4>
                    <button type="button" class="brand-modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="brand-modal-body">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div id="modalMenuPhotoContainer">
                                <img src="" id="modalMenuPhoto" class="brand-modal-img" alt="Menu Photo">
                            </div>
                            <div id="modalMenuPlaceholder" class="brand-modal-img bg-light d-none d-flex align-items-center justify-content-center">
                                <i class="bi bi-image display-1 text-muted opacity-50"></i>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex flex-column">
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <span class="badge bg-warning text-dark border border-dark px-3 py-2 rounded-pill fw-bold" id="modalMenuDate">
                                    <i class="bi bi-calendar2-heart me-1"></i>
                                    Date
                                </span>
                            </div>
                            
                            <h5 class="fw-bold text-dark mb-3">Deskripsi Hidangan</h5>
                            <p class="text-muted fw-bold mb-4" id="modalMenuDescription">Deskripsi lengkap akan muncul di sini...</p>
                            
                            <div class="mt-auto pt-4 border-top border-2 border-dark">
                                <div class="mb-4">
                                    <label class="fw-bold text-dark mb-2 d-block">Jumlah Porsi</label>
                                    <div class="qty-selector">
                                        <button type="button" class="qty-btn" id="modalQtyMinus">
                                            <i class="bi bi-dash-lg"></i>
                                        </button>
                                        <input type="number" name="qty" id="modalQtyInput" class="qty-input" value="1" min="1" readonly>
                                        <button type="button" class="qty-btn" id="modalQtyPlus">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex flex-column gap-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted fw-bold text-uppercase">Total Harga</small>
                                        <div class="h3 fw-bold text-danger mb-0" id="modalMenuPrice">
                                            Rp 0
                                        </div>
                                    </div>
                                    
                                    @auth
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <button type="submit" name="action" value="add_to_cart" class="brand-btn w-100 py-3 bg-white text-dark">
                                                    <i class="bi bi-cart-plus me-1"></i> +Keranjang
                                                </button>
                                            </div>
                                            <div class="col-6">
                                                <button type="submit" name="action" value="buy_now" class="brand-btn brand-btn-primary w-100 py-3">
                                                    Pesan Sekarang
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <a href="{{ route('login') }}" class="brand-btn brand-btn-primary text-center text-decoration-none py-3">
                                            <i class="bi bi-box-arrow-in-right me-1"></i> Login untuk Memesan
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = new bootstrap.Modal(document.getElementById('menuDetailModal'));
    const triggers = document.querySelectorAll('.menu-detail-trigger');
    const qtyInput = document.getElementById('modalQtyInput');
    const plusBtn = document.getElementById('modalQtyPlus');
    const minusBtn = document.getElementById('modalQtyMinus');

    // Qty Logic
    plusBtn.addEventListener('click', () => {
        qtyInput.value = parseInt(qtyInput.value) + 1;
    });

    minusBtn.addEventListener('click', () => {
        if (parseInt(qtyInput.value) > 1) {
            qtyInput.value = parseInt(qtyInput.value) - 1;
        }
    });
    
    triggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const data = this.dataset;
            
            document.getElementById('modalMenuId').value = data.id;
            document.getElementById('modalMenuName').textContent = data.nama;
            document.getElementById('modalMenuDescription').textContent = data.deskripsi;
            document.getElementById('modalMenuPrice').textContent = data.harga;
            document.getElementById('modalMenuDate').innerHTML = `<i class="bi bi-calendar2-heart me-1"></i> ${data.tanggal}`;
            
            // Reset Qty
            qtyInput.value = 1;
            
            const photoImg = document.getElementById('modalMenuPhoto');
            const photoContainer = document.getElementById('modalMenuPhotoContainer');
            const placeholder = document.getElementById('modalMenuPlaceholder');
            
            if (data.foto) {
                photoImg.src = data.foto;
                photoContainer.classList.remove('d-none');
                placeholder.classList.add('d-none');
            } else {
                photoContainer.classList.add('d-none');
                placeholder.classList.remove('d-none');
                placeholder.classList.add('d-flex');
            }
            
            modal.show();
        });
    });
});
</script>
@endpush
@endsection
