@extends('layouts.app')

@section('title', 'Tambah Menu Baru')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="brand-card brand-card-blue h-100">
                <div class="card-header bg-white border-bottom border-2 border-dark py-3">
                    <h4 class="mb-0 fw-bold text-uppercase letter-spacing-1">Tambah Menu</h4>
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

                    <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Tipe Menu Toggle --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Tipe Menu</label>
                            <div class="d-flex gap-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input border-2 border-dark" type="radio" name="tipe" id="tipeSatuan" value="satuan" 
                                        {{ old('tipe', 'satuan') === 'satuan' ? 'checked' : '' }} onchange="toggleTipe()">
                                    <label class="form-check-label fw-bold" for="tipeSatuan">
                                        <i class="bi bi-box me-1"></i> Satuan
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input border-2 border-dark" type="radio" name="tipe" id="tipePaket" value="paket"
                                        {{ old('tipe') === 'paket' ? 'checked' : '' }} onchange="toggleTipe()">
                                    <label class="form-check-label fw-bold" for="tipePaket">
                                        <i class="bi bi-collection me-1"></i> Paket
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input border-2 border-dark" type="radio" name="tipe" id="tipeMingguanBatch" value="mingguan_batch"
                                        {{ old('tipe') === 'mingguan_batch' ? 'checked' : '' }} onchange="toggleTipe()">
                                    <label class="form-check-label fw-bold text-success" for="tipeMingguanBatch">
                                        <i class="bi bi-calendar-week me-1"></i> Paket Mingguan (Batch)
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Tanggal (For Satuan & Paket) --}}
                        <div class="mb-4" id="singleDateGroup">
                            <label class="form-label fw-bold text-dark">Tanggal Tersedia</label>
                            <input type="date" name="tgl_tersedia" id="tgl_tersedia" class="form-control rounded-4 border-2 border-dark p-3" 
                                value="{{ old('tgl_tersedia') }}" min="{{ date('Y-m-d') }}">
                            @error('tgl_tersedia')
                                <div class="text-danger fw-bold mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tanggal Range (For Mingguan Batch) --}}
                        <div class="mb-4" id="batchDateGroup" style="display: none;">
                            <label class="form-label fw-bold text-dark mb-3">Rentang Tanggal (Min 2 Hari)</label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">Dari Tanggal</label>
                                    <input type="date" name="batch_start_date" id="batch_start_date" class="form-control rounded-4 border-2 border-dark p-3" 
                                        min="{{ date('Y-m-d') }}" onchange="generateBatchForms()">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold">Sampai Tanggal</label>
                                    <input type="date" name="batch_end_date" id="batch_end_date" class="form-control rounded-4 border-2 border-dark p-3" 
                                        min="{{ date('Y-m-d') }}" onchange="generateBatchForms()">
                                </div>
                            </div>
                            <div class="form-text mt-2 ps-2">Sistem akan membuatkan form input untuk setiap hari dalam rentang ini.</div>
                        </div>

                        {{-- SATUAN SECTION --}}
                        <div id="satuanSection">
                            <div class="mb-5">
                                <label class="form-label fw-bold text-dark">Pilih Produk</label>
                                <select name="product_id" class="form-select rounded-4 border-2 border-dark p-3 @error('product_id') is-invalid @enderror">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->product_id }}" {{ old('product_id') == $product->product_id ? 'selected' : '' }}>
                                            {{ $product->nama }} - {{ $product->formatted_harga }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                @enderror
                                <div class="form-text mt-2 ps-2">Pastikan produk sudah dibuat sebelumnya di menu <strong>Kelola Produk</strong>.</div>
                            </div>
                        </div>

                        {{-- PAKET SECTION --}}
                        <div id="paketSection" style="display: none;">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark">Nama Paket</label>
                                <input type="text" name="nama_paket" class="form-control rounded-4 border-2 border-dark p-3 @error('nama_paket') is-invalid @enderror" 
                                    placeholder="Contoh: Paket Hemat Nasi Campur" value="{{ old('nama_paket') }}">
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
                                                {{ is_array(old('product_ids')) && in_array($product->product_id, old('product_ids')) ? 'checked' : '' }}
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
                                <input type="file" name="foto_paket" id="foto_paket" class="form-control rounded-4 border-2 border-dark p-3" 
                                    accept="image/jpeg,image/png,image/jpg">
                                @error('foto_paket')
                                    <div class="text-danger fw-bold mt-2 small">{{ $message }}</div>
                                @enderror
                                <div class="form-text mt-2 ps-2">Upload foto baru untuk menu paket (JPG/PNG, max 2MB).</div>
                                <div id="fotoPreview" class="mt-3" style="display: none;">
                                    <img id="fotoPreviewImg" class="rounded-4 border border-2 border-dark" style="max-width: 100%; max-height: 200px; object-fit: cover;">
                                </div>
                            </div>
                        </div>

                        {{-- BATCH MINGGUAN SECTION --}}
                        <div id="batchMingguanSection" style="display: none;">
                            <div id="batchFormsContainer">
                                <div class="text-center py-5 text-muted border border-2 border-dark rounded-4 bg-light bg-opacity-50 border-dashed">
                                    <i class="bi bi-calendar2-range display-4 d-block mb-3 opacity-50"></i>
                                    <p class="fw-bold mb-0">Silakan pilih Rentang Tanggal di atas untuk memunculkan form pengisian.</p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.menus.index') }}" class="brand-btn brand-btn-warning text-decoration-none">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="brand-btn brand-btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Simpan Menu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// For Batch logic to pass product list to JS
const availableProducts = @json($products);

function toggleTipe() {
    const tipe = document.querySelector('input[name="tipe"]:checked')?.value || 'satuan';
    
    // UI section toggles
    document.getElementById('satuanSection').style.display = tipe === 'satuan' ? 'block' : 'none';
    document.getElementById('paketSection').style.display = tipe === 'paket' ? 'block' : 'none';
    document.getElementById('batchMingguanSection').style.display = tipe === 'mingguan_batch' ? 'block' : 'none';
    
    // Date group toggles
    if (tipe === 'mingguan_batch') {
        document.getElementById('singleDateGroup').style.display = 'none';
        document.getElementById('batchDateGroup').style.display = 'block';
        
        // Remove required from single date
        document.getElementById('tgl_tersedia').removeAttribute('required');
        document.getElementById('batch_start_date').setAttribute('required', 'required');
        document.getElementById('batch_end_date').setAttribute('required', 'required');
        
        // Generate if dates already exist
        generateBatchForms();
    } else {
        document.getElementById('singleDateGroup').style.display = 'block';
        document.getElementById('batchDateGroup').style.display = 'none';
        
        document.getElementById('tgl_tersedia').setAttribute('required', 'required');
        document.getElementById('batch_start_date').removeAttribute('required');
        document.getElementById('batch_end_date').removeAttribute('required');
    }
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

    // Init on load
    toggleTipe();
    updatePaketInfo();
});

// Batch mingguan form generation
function generateBatchForms() {
    const startDateVal = document.getElementById('batch_start_date').value;
    const endDateVal = document.getElementById('batch_end_date').value;
    const container = document.getElementById('batchFormsContainer');
    
    // Only generate if both dates are valid and start <= end
    if (!startDateVal || !endDateVal || new Date(startDateVal) > new Date(endDateVal)) {
        container.innerHTML = `
            <div class="text-center py-5 text-muted border border-2 border-dark rounded-4 bg-light bg-opacity-50 border-dashed">
                <i class="bi bi-calendar2-range display-4 d-block mb-3 opacity-50"></i>
                <p class="fw-bold mb-0">Silakan pilih Rentang Tanggal yang valid untuk memunculkan form.</p>
            </div>
        `;
        return;
    }
    
    const start = new Date(startDateVal);
    const end = new Date(endDateVal);
    
    // Don't allow more than 14 days to prevent abuse/performance issues
    const diffTime = Math.abs(end - start);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
    
    if (diffDays > 14) {
        alert("Pilih maksimal 14 hari sekaligus.");
        document.getElementById('batch_end_date').value = '';
        return;
    }

    container.innerHTML = ''; // Clear existing
    
    let currentDate = new Date(start);
    let index = 0;
    
    // Format options for date display
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', timeZone: 'Asia/Jakarta' };
    
    while (currentDate <= end) {
        // Build YYYY-MM-DD
        const yyyy = currentDate.getFullYear();
        const mm = String(currentDate.getMonth() + 1).padStart(2, '0');
        const dd = String(currentDate.getDate()).padStart(2, '0');
        const dateString = `${yyyy}-${mm}-${dd}`;
        
        // Build pretty date (e.g. "Senin, 10 Agustus 2023")
        const prettyDate = currentDate.toLocaleDateString('id-ID', options);
        
        // Generate product checkboxes HTML
        let productsHtml = '';
        availableProducts.forEach(product => {
            productsHtml += `
                <div class="form-check mb-2 p-2 rounded-3 product-check-item">
                    <input class="form-check-input border-2 border-dark" type="checkbox" 
                        name="batch[${index}][product_ids][]" value="${product.product_id}" 
                        id="batch_${index}_product_${product.product_id}">
                    <label class="form-check-label fw-bold w-100 d-flex justify-content-between" for="batch_${index}_product_${product.product_id}">
                        <span>${product.nama}</span>
                        <span class="text-danger">Rp ${parseInt(product.harga).toLocaleString('id-ID')}</span>
                    </label>
                </div>
            `;
        });
        
        // Generate day block
        const blockHtml = `
            <div class="card mb-4 border-2 border-dark shadow-sm rounded-4 batch-day-card">
                <div class="card-header bg-success bg-opacity-10 py-3 border-bottom border-2 border-dark">
                    <h5 class="mb-0 fw-bold text-success d-flex align-items-center">
                        <i class="bi bi-calendar-event me-2"></i> ${prettyDate}
                    </h5>
                    <input type="hidden" name="batch[${index}][tanggal]" value="${dateString}">
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Nama Paket Hari Ini <span class="text-danger">*</span></label>
                        <input type="text" name="batch[${index}][nama_paket]" class="form-control rounded-4 border-2 border-dark p-3" 
                            placeholder="Contoh: Paket Senin Nasi Liwet" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Pilih Produk <span class="text-muted">(minimal 2)</span> <span class="text-danger">*</span></label>
                        <div class="border border-2 border-dark rounded-4 p-3" style="max-height: 250px; overflow-y: auto;">
                            ${productsHtml}
                        </div>
                    </div>
                    
                    <div>
                        <label class="form-label fw-bold text-dark">Foto Paket <span class="text-danger">*</span></label>
                        <input type="file" name="batch[${index}][foto_paket]" class="form-control rounded-4 border-2 border-dark p-3" 
                            accept="image/jpeg,image/png,image/jpg" required>
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', blockHtml);
        
        // Increment date
        currentDate.setDate(currentDate.getDate() + 1);
        index++;
    }
}
</script>
@endsection
