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
        <h2 class="section-title mb-1">Menu-menu Asik</h2>
        <p class="text-muted mb-4 fw-bold">Pilih hidangan gokil buat bikin hari kamu makin seru!</p>

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

    @if($tab === 'harian')
    <!-- Daily Menu Packages Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="brand-card brand-card-purple p-4 mb-4 shadow-lg border-3">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h3 class="fw-bold mb-2" style="font-family: 'Quicksand', sans-serif;"><i class="bi bi-box-seam me-2"></i>Paket Hemat Harian</h3>
                        <p class="fw-bold text-muted mb-0">Gabungin menu favorit kamu biar makin hemat! Pilih paketnya, tentuin isinya, langsung sikat!</p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <button class="brand-btn brand-btn-primary py-3 px-4 fs-5 w-100" type="button" data-bs-toggle="collapse" data-bs-target="#packageComposer" aria-expanded="false">
                            <i class="bi bi-magic me-2"></i>Pilih Paket Sekarang
                        </button>
                    </div>
                </div>

                <!-- Package Pricelist -->
                <div class="row mt-4 g-3">
                    @php
                        $packages = [
                            ['name' => 'Nasi + Sayur + Daging', 'price' => '12k', 'icon' => 'bi-lightning-charge-fill', 'color' => '#fbbf24'],
                            ['name' => 'Nasi + Daging + Daging', 'price' => '17k', 'icon' => 'bi-fire', 'color' => '#f87171'],
                            ['name' => 'Nasi + Sayur + Sayur', 'price' => '10k', 'icon' => 'bi-leaf-fill', 'color' => '#4ade80'],
                            ['name' => 'Nasi + Sayur + Telor', 'price' => '12k', 'icon' => 'bi-egg-fill', 'color' => '#fcd34d'],
                            ['name' => 'Nasi + Sayur', 'price' => '9k', 'icon' => 'bi-brightness-high-fill', 'color' => '#60a5fa'],
                            ['name' => 'Nasi + Daging Utuh', 'price' => '10k', 'icon' => 'bi-gem', 'color' => '#a78bfa'],
                        ];
                    @endphp
                    @foreach($packages as $pkg)
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-3 bg-white border border-3 border-dark rounded-4 text-center shadow-neobrutal-sm h-100 package-tip" title="{{ $pkg['name'] }}">
                            <i class="bi {{ $pkg['icon'] }} fs-3 mb-2 d-block" style="color: {{ $pkg['color'] }}"></i>
                            <div class="small fw-800 text-truncate text-dark">{{ $pkg['name'] }}</div>
                            <div class="h5 fw-900 text-danger mb-0">{{ $pkg['price'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Package Composer (Collapsible) -->
            <div class="collapse" id="packageComposer">
                <div class="brand-card bg-white p-4 border-2 border-dark mb-5 shadow-lg" style="border-style: dashed !important;">
                    <div id="step1">
                        <h4 class="fw-bold mb-4 text-center" style="font-family: 'Quicksand', sans-serif;">Step 1: Pilih Jenis Paket</h4>
                        <div class="row g-3 justify-content-center mb-4">
                            @php
                                $full_packages = [
                                    ['id' => 'nsd', 'name' => 'Nasi + Sayur + Daging', 'price' => 12000, 'items' => [['type' => 'fixed', 'category' => 'Nasi'], ['type' => 'select', 'category' => 'Sayur'], ['type' => 'select', 'category' => 'Daging']]],
                                    ['id' => 'ndd', 'name' => 'Nasi + Daging + Daging', 'price' => 17000, 'items' => [['type' => 'fixed', 'category' => 'Nasi'], ['type' => 'select', 'category' => 'Daging', 'label' => 'Daging 1'], ['type' => 'select', 'category' => 'Daging', 'label' => 'Daging 2']]],
                                    ['id' => 'nss', 'name' => 'Nasi + Sayur + Sayur', 'price' => 10000, 'items' => [['type' => 'fixed', 'category' => 'Nasi'], ['type' => 'select', 'category' => 'Sayur', 'label' => 'Sayur 1'], ['type' => 'select', 'category' => 'Sayur', 'label' => 'Sayur 2']]],
                                    ['id' => 'nst', 'name' => 'Nasi + Sayur + Telor Rebus', 'price' => 12000, 'items' => [['type' => 'fixed', 'category' => 'Nasi'], ['type' => 'select', 'category' => 'Sayur'], ['type' => 'fixed', 'category' => 'Telor Rebus']]],
                                    ['id' => 'ns', 'name' => 'Nasi + Sayur', 'price' => 9000, 'items' => [['type' => 'fixed', 'category' => 'Nasi'], ['type' => 'select', 'category' => 'Sayur']]],
                                    ['id' => 'ndu', 'name' => 'Nasi + Daging Utuh (2)', 'price' => 10000, 'items' => [['type' => 'fixed', 'category' => 'Nasi'], ['type' => 'select', 'category' => 'Daging', 'label' => 'Daging Utuh (2)']]],
                                ];
                            @endphp
                            
                            @foreach($full_packages as $fp)
                            <div class="col-md-4">
                                <label class="package-option-label w-100 cursor-pointer">
                                    <input type="radio" name="package_selection" value="{{ $fp['id'] }}" class="d-none" 
                                           data-price="{{ $fp['price'] }}" data-name="{{ $fp['name'] }}" data-config="{{ json_encode($fp['items']) }}">
                                    <div class="package-option-card p-4 text-center border border-3 border-dark rounded-4 h-100 transition-all">
                                        <h5 class="fw-bold mb-2">{{ $fp['name'] }}</h5>
                                        <div class="h4 fw-bold text-danger mb-0">Rp {{ number_format($fp['price'], 0, ',', '.') }}</div>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div id="step2Composer" class="d-none">
                        <hr class="border-2 border-dark mb-5">
                        <h4 class="fw-bold mb-4 text-center" style="font-family: 'Quicksand', sans-serif;">Step 2: Pilih Isi Paket</h4>
                        
                        <form action="{{ route('cart.store') }}" method="POST" id="bundleForm">
                            @csrf
                            <input type="hidden" name="bundle_name" id="inputBundleName">
                            <input type="hidden" name="bundle_price" id="inputBundlePrice">
                            <input type="hidden" name="qty" value="1">
                            
                            <div id="itemSelectionContainer" class="row g-4 justify-content-center">
                                <!-- Dynamic Content via JS -->
                            </div>

                            <div class="text-center mt-5">
                                <button type="submit" class="brand-btn brand-btn-primary py-3 px-5 fs-4 mt-3" id="btnAddToCartBundle" disabled>
                                    <i class="bi bi-cart-plus me-2"></i>Masukkan Keranjang
                                </button>
                                <p class="text-muted small fw-bold mt-3" id="selectionWarning"><i class="bi bi-info-circle me-1"></i>Lengkapi pilihan menu kamu buat lanjut!</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

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
                            @if($tab !== 'mingguan')
                                <a href="{{ route('menus.show', $menu->menu_id) }}" class="brand-btn brand-btn-primary text-decoration-none" onclick="event.stopPropagation();">
                                    <i class="bi bi-cart-plus me-1"></i> Pesan
                                </a>
                            @else
                                <a href="{{ route('menus.show', $menu->menu_id) }}" class="brand-btn brand-btn-primary bg-white text-dark text-decoration-none" onclick="event.stopPropagation();">
                                    Detail
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center">
                <div class="mb-4">
                    <i class="bi bi-calendar-x display-1 text-muted opacity-25"></i>
                </div>
                <h4 class="fw-bold text-dark fs-3">Belum Ada Menu Nih</h4>
                <p class="text-muted mx-auto fw-bold" style="max-width: 400px;">Yah, sepertinya belum ada menu buat tanggal ini. Cek tanggal lain yuk!</p>
                <a href="{{ route('menus.index') }}" class="brand-btn brand-btn-primary text-white text-decoration-none mt-3">
                    Lihat Semua Menu
                </a>
            </div>
        @endforelse
    </div>

    @if($tab === 'mingguan' && $menus->count() > 0)
        <div class="mt-5 py-5 border-top border-2 border-dark text-center">
            <div class="brand-card d-inline-block px-5 py-4 bg-light shadow-lg">
                <h3 class="fw-bold mb-3">Siap untuk Berlangganan Seminggu?</h3>
                <p class="text-muted fw-bold mb-4">Klik tombol di bawah untuk memasukkan semua paket minggu ini ke keranjang Anda.</p>
                <form action="{{ route('cart.store') }}" method="POST">
                    @csrf
                    @foreach($menus as $menu)
                        <input type="hidden" name="menu_ids[]" value="{{ $menu->menu_id }}">
                    @endforeach
                    <button type="submit" class="brand-btn brand-btn-primary py-3 px-5 fs-4">
                        <i class="bi bi-bag-check-fill me-2"></i> Pesan Paket
                    </button>
                </form>
            </div>
        </div>
    @endif

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
                            
                            <h5 class="fw-bold text-dark mb-3">Deskripsi Masakan</h5>
                            <p class="text-muted fw-bold mb-4" id="modalMenuDescription">Deskripsi lengkap bakal muncul di sini...</p>
                            
                            <div class="mt-auto pt-4 border-top border-2 border-dark">
                                <div class="mb-4">
                                    <label class="fw-bold text-dark mb-2 d-block">Mau Berapa Porsi?</label>
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
                                        @if($tab !== 'mingguan')
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <button type="submit" name="action" value="add_to_cart" class="brand-btn w-100 py-3 bg-white text-dark">
                                                        <i class="bi bi-cart-plus me-1"></i> +Keranjang
                                                    </button>
                                                </div>
                                                <div class="col-6">
                                                    <button type="submit" name="action" value="buy_now" class="brand-btn brand-btn-primary w-100 py-3">
                                                        Sikat!
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-info border-2 border-dark rounded-4 fw-bold">
                                                <i class="bi bi-info-circle-fill me-2"></i> Menu ini adalah bagian dari paket mingguan. Gunakan tombol di halaman utama untuk memesan seluruh paket hari ini hingga seminggu ke depan.
                                            </div>
                                        @endif
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

@push('styles')
<style>
    .brand-card-purple { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); color: white; border: 3px solid #000; box-shadow: 8px 8px 0 #000; }
    .brand-card.border-dashed { border-style: dashed !important; border-width: 4px !important; border-color: #000 !important; }
    .package-option-label:hover .package-option-card { border-color: #000 !important; transform: translate(-4px, -4px); box-shadow: 8px 8px 0 #000; background: #fff; }
    .package-option-label input:checked + .package-option-card { background: #000; color: #fff; transform: translate(4px, 4px); box-shadow: none; }
    .package-option-label input:checked + .package-option-card .text-danger { color: #f87171 !important; }
    .transition-all { transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .cursor-pointer { cursor: pointer; }
    .item-select-card { background: #fff; border: 3px solid #000; border-radius: 16px; box-shadow: 4px 4px 0 #000; transition: all 0.2s; }
    .item-select-card:hover { transform: translate(-2px, -2px); box-shadow: 6px 6px 0 #000; }
    .item-select-card select { border: none !important; box-shadow: none !important; font-weight: 800; cursor: pointer; background: transparent; font-size: 1.1rem; }
    .package-tip { transition: transform 0.2s; }
    .package-tip:hover { transform: scale(1.05) rotate(2deg); z-index: 10; cursor: default; }
    .shadow-neobrutal-sm { box-shadow: 4px 4px 0 #000; }
    .fw-800 { font-weight: 800; }
    .fw-900 { font-weight: 900; }
    .bg-purple { background-color: #a855f7 !important; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Current available menus for composer
    const availableMenus = @json($allHarianMenus);
    
    // Package Composition Logic
    const packageRadios = document.querySelectorAll('input[name="package_selection"]');
    const step2 = document.getElementById('step2Composer');
    const itemContainer = document.getElementById('itemSelectionContainer');
    const bundleForm = document.getElementById('bundleForm');
    const btnSubmit = document.getElementById('btnAddToCartBundle');
    const warning = document.getElementById('selectionWarning');

    packageRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const config = JSON.parse(this.dataset.config);
            const price = this.dataset.price;
            const name = this.dataset.name;
            
            // Show Step 2
            step2.classList.remove('d-none');
            step2.scrollIntoView({ behavior: 'smooth', block: 'start' });
            
            // Generate Selection UI
            generateItemSelection(config);
            
            // Update hidden inputs
            document.getElementById('inputBundleName').value = name;
            document.getElementById('inputBundlePrice').value = price;
            
            validateSelection();
        });
    });

    function generateItemSelection(config) {
        itemContainer.innerHTML = '';
        
        config.forEach((step, index) => {
            const col = document.createElement('div');
            col.className = 'col-md-5';
            
            let content = '';
            if (step.type === 'fixed') {
                // Find fixed item menu_id if applicable (e.g. Nasi)
                const fixedItem = availableMenus.find(m => m.product.kategori === step.category) || 
                                  availableMenus.find(m => m.product.nama.toLowerCase().includes(step.category.toLowerCase()));
                
                content = `
                    <div class="item-select-card p-4 bg-light text-center">
                        <h6 class="text-muted fw-bold mb-1">${step.label || step.category}</h6>
                        <h5 class="fw-bold mb-0">${fixedItem ? fixedItem.product.nama : step.category}</h5>
                        ${fixedItem ? `<input type="hidden" name="menu_ids[]" value="${fixedItem.menu_id}">` : ''}
                    </div>
                `;
            } else {
                // Selectable menu
                const filtered = availableMenus.filter(m => m.product.kategori === step.category);
                
                content = `
                    <div class="item-select-card p-4 bg-white">
                        <label class="d-block text-muted small fw-bold mb-2">${step.label || step.category}</label>
                        <select class="form-select form-select-lg menu-selector" name="menu_ids[]" required>
                            <option value="" disabled selected>Pilih ${step.category}...</option>
                            ${filtered.map(m => `<option value="${m.menu_id}">${m.product.nama}</option>`).join('')}
                        </select>
                    </div>
                `;
            }
            
            col.innerHTML = content;
            itemContainer.appendChild(col);
        });

        // re-bind validation
        document.querySelectorAll('.menu-selector').forEach(select => {
            select.addEventListener('change', validateSelection);
        });
    }

    function validateSelection() {
        const selects = document.querySelectorAll('.menu-selector');
        let allValid = true;
        selects.forEach(s => { if(!s.value) allValid = false; });
        
        btnSubmit.disabled = !allValid;
        if(allValid) {
            warning.innerHTML = '<i class="bi bi-check-circle-fill me-1 text-success"></i> Mantap! Pilihan kamu sudah lengkap. Sikat!';
            warning.classList.remove('text-muted');
            warning.classList.add('text-success');
        } else {
            warning.innerHTML = '<i class="bi bi-info-circle me-1"></i> Lengkapi pilihan menu kamu buat lanjut!';
            warning.classList.add('text-muted');
            warning.classList.remove('text-success');
        }
    }

    // Existing Modal Qty Logic...
    const modal = new bootstrap.Modal(document.getElementById('menuDetailModal'));
    const triggers = document.querySelectorAll('.menu-detail-trigger');
    const qtyInput = document.getElementById('modalQtyInput');
    const plusBtn = document.getElementById('modalQtyPlus');
    const minusBtn = document.getElementById('modalQtyMinus');

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
