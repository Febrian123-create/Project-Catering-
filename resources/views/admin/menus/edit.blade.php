@extends('layouts.app')

@section('title', 'Edit Menu')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="brand-card brand-card-purple h-100">
                <div class="card-header bg-white border-bottom border-2 border-dark py-3">
                    <h4 class="mb-0 fw-bold text-uppercase letter-spacing-1">Edit Menu</h4>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-4 border-2 border-dark mb-4">
                            <ul class="mb-0 fw-bold">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.menus.update', $menu) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Tipe Menu Toggle --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Tipe Menu</label>
                            <div class="d-flex gap-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input border-2 border-dark" type="radio" name="tipe" id="tipeSatuan" value="satuan" 
                                        {{ old('tipe', $menu->tipe) === 'satuan' ? 'checked' : '' }} onchange="toggleTipe()">
                                    <label class="form-check-label fw-bold" for="tipeSatuan">
                                        <i class="bi bi-box me-1"></i> Satuan
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input border-2 border-dark" type="radio" name="tipe" id="tipePaket" value="paket"
                                        {{ old('tipe', $menu->tipe) === 'paket' ? 'checked' : '' }} onchange="toggleTipe()">
                                    <label class="form-check-label fw-bold" for="tipePaket">
                                        <i class="bi bi-collection me-1"></i> Paket
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Tanggal --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Tanggal Tersedia</label>
                            <input type="date" name="tgl_tersedia" class="form-control rounded-4 border-2 border-dark p-3 @error('tgl_tersedia') is-invalid @enderror" 
                                value="{{ old('tgl_tersedia', $menu->tgl_tersedia->format('Y-m-d')) }}" required>
                            @error('tgl_tersedia')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- SATUAN SECTION --}}
                        <div id="satuanSection">
                            <div class="mb-5">
                                <label class="form-label fw-bold text-dark">Pilih Produk</label>
                                <select name="product_id" class="form-select rounded-4 border-2 border-dark p-3 @error('product_id') is-invalid @enderror">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->product_id }}" {{ old('product_id', $menu->product_id) == $product->product_id ? 'selected' : '' }}>
                                            {{ $product->nama }} - {{ $product->formatted_harga }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- PAKET SECTION --}}
                        @php
                            $selectedProductIds = old('product_ids', $menu->products->pluck('product_id')->toArray());
                        @endphp
                        <div id="paketSection" style="display: none;">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">Nama Paket</label>
                                <input type="text" name="nama_paket" class="form-control rounded-4 border-2 border-dark p-3 @error('nama_paket') is-invalid @enderror" 
                                    placeholder="Contoh: Paket Hemat Nasi Campur" value="{{ old('nama_paket', $menu->nama_paket) }}">
                                @error('nama_paket')
                                    <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">Pilih Produk (minimal 2)</label>
                                <div class="border border-2 border-dark rounded-4 p-3" style="max-height: 300px; overflow-y: auto;">
                                    @foreach($products as $product)
                                        <div class="form-check mb-2 p-2 rounded-3 product-check-item">
                                            <input class="form-check-input border-2 border-dark product-checkbox" type="checkbox" 
                                                name="product_ids[]" value="{{ $product->product_id }}" 
                                                id="product_{{ $product->product_id }}"
                                                data-harga="{{ $product->harga }}"
                                                data-nama="{{ $product->nama }}"
                                                data-deskripsi="{{ $product->deskripsi }}"
                                                {{ in_array($product->product_id, $selectedProductIds) ? 'checked' : '' }}
                                                onchange="updatePaketInfo()">
                                            <label class="form-check-label fw-bold w-100 d-flex justify-content-between" for="product_{{ $product->product_id }}">
                                                <span>{{ $product->nama }}</span>
                                                <span class="text-danger">{{ $product->formatted_harga }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('product_ids')
                                    <div class="text-danger fw-bold mt-2 small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Auto-calculated info --}}
                            <div class="mb-4 p-3 rounded-4 border border-2 border-dark bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-dark"><i class="bi bi-calculator me-2"></i>Total Harga Paket:</span>
                                    <span class="fw-bold text-danger fs-5" id="totalHarga">Rp 0</span>
                                </div>
                                <div>
                                    <span class="fw-bold text-dark small"><i class="bi bi-card-text me-2"></i>Deskripsi (auto-merged):</span>
                                    <p class="text-muted small mb-0 mt-1" id="mergedDeskripsi">-</p>
                                </div>
                            </div>

                            <div class="mb-5">
                                <label class="form-label fw-bold text-dark">Foto Paket <span class="text-danger">(upload baru)</span></label>
                                @if($menu->foto_paket)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $menu->foto_paket) }}" class="rounded-4 border border-2 border-dark" 
                                            style="max-width: 100%; max-height: 200px; object-fit: cover;" alt="Foto paket saat ini">
                                        <p class="form-text mt-1">Foto saat ini. Upload file baru untuk mengganti.</p>
                                    </div>
                                @endif
                                <input type="file" name="foto_paket" class="form-control rounded-4 border-2 border-dark p-3 @error('foto_paket') is-invalid @enderror" 
                                    accept="image/jpeg,image/png,image/jpg">
                                @error('foto_paket')
                                    <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                @enderror
                                <div id="fotoPreview" class="mt-3" style="display: none;">
                                    <img id="fotoPreviewImg" class="rounded-4 border border-2 border-dark" style="max-width: 100%; max-height: 200px; object-fit: cover;">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.menus.index') }}" class="brand-btn brand-btn-warning text-decoration-none">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="brand-btn brand-btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Update Menu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleTipe() {
    const tipe = document.querySelector('input[name="tipe"]:checked').value;
    document.getElementById('satuanSection').style.display = tipe === 'satuan' ? 'block' : 'none';
    document.getElementById('paketSection').style.display = tipe === 'paket' ? 'block' : 'none';
}

function updatePaketInfo() {
    const checkboxes = document.querySelectorAll('.product-checkbox:checked');
    let total = 0;
    let descriptions = [];
    
    checkboxes.forEach(cb => {
        total += parseInt(cb.dataset.harga);
        descriptions.push(cb.dataset.nama + ': ' + (cb.dataset.deskripsi || '-'));
    });
    
    document.getElementById('totalHarga').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('mergedDeskripsi').textContent = descriptions.length > 0 ? descriptions.join(' | ') : '-';
}

// Foto preview
document.addEventListener('DOMContentLoaded', function() {
    const fotoInput = document.querySelector('input[name="foto_paket"]');
    if (fotoInput) {
        fotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    document.getElementById('fotoPreviewImg').src = ev.target.result;
                    document.getElementById('fotoPreview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    toggleTipe();
    updatePaketInfo();
});
</script>
@endsection
