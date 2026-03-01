@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="sticker-container">
    <i class="bi bi-wallet2 sticker sticker-1"></i>
    <i class="bi bi-geo-alt sticker sticker-2"></i>
    <i class="bi bi-box-seam sticker sticker-3"></i>
    <i class="bi bi-truck sticker sticker-4"></i>
    <i class="bi bi-check-circle sticker sticker-5"></i>
</div>

<div class="container py-5 text-black">
    <div class="mb-5">
        <h1 class="fw-black text-dark mb-2 display-5 fw-bold" style="letter-spacing: -2px;">CHECKOUT</h1>
        <p class="text-muted fw-bold">Konfirmasi pesanan Anda dan lengkapi detail pengiriman.</p>
    </div>

    <div class="row g-5">
        <div class="col-lg-8">
            <div class="brand-card mb-5">
                <div class="bg-yellow border-bottom border-2 border-dark p-3" style="background: var(--fh-yellow);">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-geo-alt-fill me-2"></i>Informasi Pengiriman</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="fw-bold text-dark mb-3 d-block">Metode Pengantaran <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="metode_pengantaran" id="method_delivery" value="antar_alamat" checked autocomplete="off">
                                    <label class="brand-btn brand-btn-primary w-100 py-3 fs-6 d-flex align-items-center justify-content-center gap-2" for="method_delivery">
                                        <i class="bi bi-truck fs-4"></i> Antar ke Alamat
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="metode_pengantaran" id="method_pickup" value="ambil_eureka" autocomplete="off">
                                    <label class="brand-btn brand-btn-warning w-100 py-3 fs-6 d-flex align-items-center justify-content-center gap-2" for="method_pickup">
                                        <i class="bi bi-box-seam fs-4"></i> Ambil di Eureka
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Delivery Address --}}
                        <div id="section_delivery" class="mb-4">
                            <label class="fw-bold text-dark mb-2">Alamat Lengkap <span class="text-danger">*</span></label>
                            <div class="alert alert-info border-2 border-dark rounded-brand bg-light-subtle d-flex align-items-center gap-2 mb-3 py-2 fw-bold small">
                                <i class="bi bi-info-circle-fill text-primary"></i>
                                Catatan: Pengantaran hanya mencakup area sekitar Maranatha.
                            </div>
                            <textarea name="alamat_pengiriman" class="form-control brand-input @error('alamat_pengiriman') is-invalid @enderror" 
                                rows="3" placeholder="Masukkan alamat detail (Jalan, No Rumah, RT/RW)">{{ old('alamat_pengiriman', Auth::user()->alamat_default) }}</textarea>
                            @error('alamat_pengiriman')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Section: Pickup Time --}}
                        <div id="section_pickup" class="mb-4 d-none">
                            <label class="fw-bold text-dark mb-2">Jam Pengambilan <span class="text-danger">*</span></label>
                            <select name="jam_pengambilan" id="pickupTimeSelect" class="form-select brand-input brand-select @error('jam_pengambilan') is-invalid @enderror">
                                <option value="" selected disabled>Pilih Jam Pengambilan</option>
                                @php
                                    // 2 = Tuesday in PHP (0=Sunday, 1=Monday, 2=Tuesday...)
                                    $isSelasa = (int) now()->format('N') === 2;
                                @endphp
                                <option value="08.00-09.15" {{ old('jam_pengambilan') == '08.00-09.15' ? 'selected' : '' }}>08.00 - 09.15 WIB</option>
                                @if(!$isSelasa)
                                <option value="11.00-12.30" {{ old('jam_pengambilan') == '11.00-12.30' ? 'selected' : '' }}>11.00 - 12.30 WIB</option>
                                @endif
                            </select>
                            @error('jam_pengambilan')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                            <div class="mt-2 small fw-bold text-muted">
                                <i class="bi bi-geo-alt-fill me-1"></i> Lokasi: Kantin Eureka (Gedung G Lt. 1)
                                <br>
                                <i class="bi bi-clock-fill me-1"></i> Kalau kamu tidak ambil di jam yang sudah dipilih, mohon ambil pesanan di tempat penitipan barang basement gedung G, ya!
                                @if($isSelasa)
                                <span class="badge bg-warning text-dark border border-dark ms-2">Hari ini (Selasa): hanya sesi pagi</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="fw-bold text-dark mb-2">Catatan Tambahan (Opsional)</label>
                            <textarea name="notes" class="form-control brand-input" rows="2" 
                                placeholder="Contoh: Titip di satpam, jangan pedas, dll.">{{ old('notes') }}</textarea>
                        </div>
                    </form>
                </div>
            </div>

            <div class="brand-card mb-5">
                <div class="bg-green border-bottom border-2 border-dark p-3" style="background: var(--fh-green);">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-cart-check-fill me-2"></i>Rincian Pesanan</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="table-responsive">
                        <table class="table brand-table mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-0 pt-0">Menu</th>
                                    <th class="text-center pt-0">Porsi</th>
                                    <th class="text-end pe-0 pt-0">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                    <tr>
                                        <td class="ps-0">
                                            <div class="fw-bold text-dark">{{ $item->menu->nama_display ?? 'Menu Tidak Tersedia' }}</div>
                                            <small class="text-muted fw-bold">
                                                <i class="bi bi-calendar-event me-1"></i>
                                                {{ $item->menu ? $item->menu->tgl_tersedia->format('d M Y') : '-' }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('cart.update', $item->menu_id) }}" method="POST" id="form-update-{{ $item->cart_id ?? $item->menu_id }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="redirect_to" value="checkout">
                                                @if($item->bundle_id)
                                                    <input type="hidden" name="bundle_id" value="{{ $item->bundle_id }}">
                                                @endif
                                                <div class="qty-selector d-inline-flex border border-2 border-dark rounded-pill overflow-hidden bg-white mt-1">
                                                    <button type="button" class="btn btn-sm btn-light border-0 fw-bold px-2 py-0" onclick="updateCheckoutQty('{{ $item->cart_id ?? $item->menu_id }}', -1)"><i class="bi bi-dash"></i></button>
                                                    <input type="number" name="qty" id="qty-{{ $item->cart_id ?? $item->menu_id }}" class="form-control border-0 text-center fw-bold p-0" 
                                                        value="{{ $item->qty }}" min="1" style="width: 35px; box-shadow: none;"
                                                        onchange="this.form.submit()" readonly>
                                                    <button type="button" class="btn btn-sm btn-light border-0 fw-bold px-2 py-0" onclick="updateCheckoutQty('{{ $item->cart_id ?? $item->menu_id }}', 1)"><i class="bi bi-plus"></i></button>
                                                </div>
                                            </form>
                                            @if($item->bundle_id)
                                                <div class="small text-muted mt-1 fw-bold">(Set Paket)</div>
                                            @endif
                                        </td>
                                        <td class="text-end pe-0 align-middle">
                                            <span class="fw-bold text-dark">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="brand-card sticky-top shadow-lg overflow-visible" style="top: 100px;">
                <div class="bg-blue border-bottom border-2 border-dark p-3" style="background: var(--fh-blue);">
                    <h5 class="fw-black mb-0 text-dark"><i class="bi bi-cash-stack me-2"></i>TOTAL PEMBAYARAN</h5>
                </div>
                <div class="card-body p-4">
                    <div class="summary-box mb-4">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold text-muted">Subtotal ({{ $cartItems->sum('qty') }} porsi)</span>
                            <span class="fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold text-muted">Biaya Pengiriman</span>
                            <span class="badge bg-green-subtle text-green border border-dark rounded-pill px-3 py-1 fw-bold fw-bold" style="background: var(--fh-green);">GRATIS</span>
                        </div>
                        <div class="mt-4 pt-4 border-top border-2 border-dark">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 fw-bold mb-0 text-dark">Total Tagihan</span>
                                <span class="h3 fw-bold text-danger mb-0 shadow-text">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-3">
                        <button type="submit" form="checkoutForm" class="brand-btn brand-btn-primary py-3 fs-5">
                            Konfirmasi Pesanan <i class="bi bi-check2-circle ms-1"></i>
                        </button>
                        <a href="{{ route('cart.index') }}" class="brand-btn py-3 bg-white text-dark text-center text-decoration-none">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="bg-light p-3 border-top border-2 border-dark text-center">
                   <p class="small text-muted fw-bold mb-0">
                        <i class="bi bi-shield-check me-1"></i>
                        Verifikasi pembayaran otomatis dan cepat.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const methodDelivery = document.getElementById('method_delivery');
        const methodPickup = document.getElementById('method_pickup');
        const sectionDelivery = document.getElementById('section_delivery');
        const sectionPickup = document.getElementById('section_pickup');

        function toggleSections() {
            if (methodDelivery.checked) {
                sectionDelivery.classList.remove('d-none');
                sectionPickup.classList.add('d-none');
                
                // Set required
                sectionDelivery.querySelector('textarea').setAttribute('required', 'required');
                sectionPickup.querySelector('select').removeAttribute('required');
            } else {
                sectionDelivery.classList.add('d-none');
                sectionPickup.classList.remove('d-none');
                
                // Set required
                sectionDelivery.querySelector('textarea').removeAttribute('required');
                sectionPickup.querySelector('select').setAttribute('required', 'required');
            }
        }

        methodDelivery.addEventListener('change', toggleSections);
        methodPickup.addEventListener('change', toggleSections);

        // Initial trigger
        toggleSections();
    });

    function updateCheckoutQty(id, change) {
        let input = document.getElementById('qty-' + id);
        let form = document.getElementById('form-update-' + id);
        let currentVal = parseInt(input.value);
        let newVal = currentVal + change;
        
        if(newVal >= 1) {
            input.value = newVal;
            form.submit();
        }
    }
</script>
@endpush
