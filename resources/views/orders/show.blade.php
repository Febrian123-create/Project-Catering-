@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_id)

@section('content')
<div class="sticker-container">
    <i class="bi bi-receipt sticker sticker-1"></i>
    <i class="bi bi-credit-card sticker sticker-2"></i>
    <i class="bi bi-box sticker sticker-3"></i>
    <i class="bi bi-stars sticker sticker-4"></i>
    <i class="bi bi-shield-check sticker sticker-5"></i>
</div>

<div class="container py-5 text-black">
    <nav aria-label="breadcrumb" class="mb-5">
        <ol class="breadcrumb brand-breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-dark">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none text-dark">Pesanan Saya</a></li>
            <li class="breadcrumb-item active fw-bold text-danger">Detail #{{ $order->order_id }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Info Utama Pesanan -->
        <div class="col-lg-8">
            <div class="brand-card shadow-lg mb-5 overflow-hidden">
                <div class="bg-yellow border-bottom border-2 border-dark p-4 d-flex justify-content-between align-items-center" style="background: var(--fh-yellow);">
                    <div>
                        <span class="fw-bold text-dark small text-uppercase opacity-75 d-block">ORDER ID</span>
                        <h2 class="fw-black mb-0 text-dark" style="letter-spacing: -1px;">#{{ $order->order_id }}</h2>
                    </div>
                    <div>
                        {!! $order->status_badge !!}
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <div class="row g-4 mb-5">
                        <div class="col-md-6 border-start border-4 border-primary ps-3">
                            <small class="text-muted fw-bold d-block mb-1 text-uppercase">Tanggal Pesanan</small>
                            <div class="fw-bold text-dark fs-5"><i class="bi bi-calendar-check me-2 text-primary"></i>{{ $order->tgl_pesan->format('d F Y') }}</div>
                        </div>
                        <div class="col-md-6 border-start border-4 border-green ps-3" style="border-color: var(--fh-green) !important;">
                            <small class="text-muted fw-bold d-block mb-1 text-uppercase">Status Pembayaran</small>
                            <div class="fw-bold fs-5">
                                @if($order->status_pembayaran == 'pending')
                                    <span class="text-warning shadow-text-sm"><i class="bi bi-hourglass-split me-2"></i>Menunggu</span>
                                @elseif($order->status_pembayaran == 'paid')
                                    <span class="text-green shadow-text-sm"><i class="bi bi-check-circle-fill me-2"></i>Lunas</span>
                                @else
                                    <span class="text-danger shadow-text-sm"><i class="bi bi-x-circle-fill me-2"></i>Batal</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 border-start border-4 border-blue ps-3" style="border-color: var(--fh-blue) !important;">
                            <small class="text-muted fw-bold d-block mb-1 text-uppercase">Metode Pengantaran</small>
                            <div class="fw-bold text-dark fs-5">
                                @if($order->metode_pengantaran == 'ambil_eureka')
                                    <i class="bi bi-box-seam me-2 text-primary"></i>Ambil di Eureka
                                @else
                                    <i class="bi bi-truck me-2 text-primary"></i>Antar ke Alamat
                                @endif
                            </div>
                        </div>
                        @if($order->metode_pengantaran == 'ambil_eureka')
                        <div class="col-md-6 border-start border-4 border-warning ps-3" style="border-color: var(--fh-yellow) !important;">
                            <small class="text-muted fw-bold d-block mb-1 text-uppercase">Jam Pengambilan</small>
                            <div class="fw-bold text-dark fs-5"><i class="bi bi-clock-history me-2 text-warning"></i>{{ $order->jam_pengambilan }} WIB</div>
                        </div>
                        @endif
                        <div class="col-12 mt-4">
                            <small class="text-muted fw-bold d-block mb-2 text-uppercase">Alamat / Lokasi</small>
                            <div class="p-3 bg-light rounded-4 border-2 border border-dark">
                                <i class="bi bi-geo-alt-fill me-2 text-red" style="color: var(--fh-red);"></i><span class="fw-medium">{{ $order->alamat_pengiriman }}</span>
                            </div>
                        </div>
                        @if($order->notes)
                        <div class="col-12">
                            <small class="text-muted fw-bold d-block mb-2 text-uppercase">Catatan Pembeli</small>
                            <div class="p-3 bg-light border-start border-4 border-dark italic rounded-3 rounded-start-0 fw-bold">"{{ $order->notes }}"</div>
                        </div>
                        @endif
                    </div>

                    <div class="mt-5">
                        <h4 class="fw-black text-dark mb-4" style="letter-spacing: -1px;"><i class="bi bi-journal-text me-2"></i>Kamu pesen apa aja?</h4>

                        {{-- Session flash messages shown OUTSIDE modal loop --}}
                        @if(session('success'))
                            <div class="alert alert-success border-2 border-dark rounded-4 mb-4 fw-bold">
                                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger border-2 border-dark rounded-4 mb-4 fw-bold">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger border-2 border-dark rounded-4 mb-4 fw-bold text-start">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row g-3">
                            @foreach($order->orderDetails as $detail)
                                <div class="col-12">
                                    <div class="brand-card bg-white p-3 hover-lift border-1">
                                        <div class="row align-items-center g-3">
                                            <div class="col-md-6">
                                                <h6 class="fw-bold mb-1 text-dark">{{ $detail->menu->nama_display }}</h6>
                                                <p class="text-muted small mb-0 fw-bold">{{ $detail->menu->tgl_tersedia->format('d M Y') }}</p>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <span class="badge bg-white text-dark border-2 border border-dark px-3 py-2 rounded-pill fw-bold">{{ $detail->qty }} Porsi</span>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <span class="fw-bold text-danger">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                @if($order->status_pesanan === 'terkirim')
                                                    <button type="button" class="brand-btn brand-btn-warning btn-sm w-100 py-2 fs-6 review-btn"
                                                        data-modal-target="reviewModal{{ $detail->menu_id }}">
                                                        Review
                                                    </button>
                                                @else
                                                    <span class="badge bg-light text-muted border border-dark rounded-pill px-2 py-1 small fw-bold w-100">
                                                        <i class="bi bi-lock-fill me-1"></i>Belum Terkirim
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- JS to open modals only via explicit button click --}}
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Wire up review buttons via JS only (no data-bs-toggle auto-trigger)
                                document.querySelectorAll('.review-btn').forEach(function(btn) {
                                    btn.addEventListener('click', function() {
                                        var targetId = this.getAttribute('data-modal-target');
                                        var modalEl = document.getElementById(targetId);
                                        if (modalEl) { new bootstrap.Modal(modalEl).show(); }
                                    });
                                });

                                @if($errors->any() && old('target_menu_id'))
                                // Re-open the correct modal if there were validation errors
                                var errModalEl = document.getElementById('reviewModal{{ old("target_menu_id") }}');
                                if (errModalEl) { new bootstrap.Modal(errModalEl).show(); }
                                @endif
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Pembayaran -->
        <div class="col-lg-4">
            <div class="brand-card sticky-top shadow-lg overflow-visible" style="top: 100px;">
                <div class="bg-blue border-bottom border-2 border-dark p-3" style="background: var(--fh-blue);">
                    <h5 class="fw-black mb-0 text-dark"><i class="bi bi-wallet2 me-2"></i>PEMBAYARAN</h5>
                </div>
                <div class="card-body p-4 text-center">
                    <p class="text-muted fw-bold mb-2 text-uppercase small">Total Tagihan Lunas</p>
                    <h2 class="fw-bold text-danger mb-4">{{ $order->formatted_total }}</h2>

                    @if($order->status_pembayaran == 'pending')
                        <div class="alert alert-warning border-2 border-dark rounded-4 p-3 mb-4 fw-bold small text-start shadow-sm" style="background-color: #fff3cd;">
                            <i class="bi bi-info-circle-fill me-2 fs-5"></i> Yuk, buruan bayar biar bisa langsung kita masakin buat lo!
                        </div>
                        
                        @if($paymentUrl)
                            <button id="pay-button" class="brand-btn brand-btn-primary w-100 py-3 mb-3 fs-5">
                                <i class="bi bi-shield-lock-fill me-2"></i> Bayar Sekarang
                            </button>

                            <script type="text/javascript">
                                document.getElementById('pay-button').onclick = function(){
                                    window.location.href = '{{ $paymentUrl }}';
                                };
                            </script>
                        @else
                            <div class="alert alert-danger border-2 border-dark rounded-4 p-3 mb-4 fw-bold">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> Pembayaran saat ini sedang tidak tersedia.
                            </div>
                        @endif

                        <button id="check-status-btn" class="brand-btn w-100 py-3 bg-white text-dark mb-3" onclick="checkPaymentStatus()">
                            Sudah Bayar? Cek Status
                        </button>
                        <div id="status-message" class="text-center small mb-3 fw-bold" style="display:none;"></div>

                        <script>
                            var checkUrl = '{{ route("payment.checkStatus", $order->order_id) }}';
                            var isChecking = false;

                            function checkPaymentStatus() {
                                if (isChecking) return;
                                isChecking = true;

                                var btn = document.getElementById('check-status-btn');
                                var msg = document.getElementById('status-message');
                                btn.disabled = true;
                                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mengecek...';
                                msg.style.display = 'none';

                                fetch(checkUrl)
                                    .then(function(r) { return r.json(); })
                                    .then(function(data) {
                                        if (data.is_paid) {
                                            msg.style.display = 'block';
                                            msg.className = 'text-center small mb-3 text-success fw-bold p-2 bg-success-subtle rounded border border-success';
                                            msg.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Berhasil! Segera memuat...';
                                            setTimeout(function() { window.location.reload(); }, 1500);
                                        } else {
                                            msg.style.display = 'block';
                                            msg.className = 'text-center small mb-3 text-danger fw-bold p-2 bg-danger-subtle rounded border border-danger';
                                            msg.innerHTML = '<i class="bi bi-clock-history me-1"></i> Pembayaran belum terdeteksi.';
                                            btn.disabled = false;
                                            btn.innerHTML = 'Sudah Bayar? Cek Status';
                                            isChecking = false;
                                        }
                                    })
                                    .catch(function() {
                                        btn.disabled = false;
                                        btn.innerHTML = 'Sudah Bayar? Cek Status';
                                        isChecking = false;
                                    });
                            }

                            setInterval(function() {
                                if (!isChecking) {
                                    fetch(checkUrl)
                                        .then(function(r) { return r.json(); })
                                        .then(function(data) {
                                            if (data.is_paid) { window.location.reload(); }
                                        })
                                        .catch(function() {});
                                }
                            }, 10000);
                        </script>
                    @else
                        <div class="py-4 bg-light rounded-4 border-2 border-dashed border-dark text-center my-4" style="background-color: var(--fh-green) !important; opacity: 0.8;">
                            <i class="bi bi-patch-check-fill text-success display-4 mb-3 d-block shadow-text-sm"></i>
                            <h4 class="fw-black text-dark mb-1">SIP, UDAH LUNAS!</h4>
                            <p class="text-muted fw-bold mb-0">Dapur kita langsung tancap gas buat lo!</p>
                        </div>
                    @endif
                </div>
                <div class="p-4 pt-0 border-top border-2 border-dark border-opacity-10 text-center mt-3">
                    <a href="{{ route('orders.index') }}" class="text-dark fw-bold text-decoration-none small hover-underline">
                        <i class="bi bi-arrow-left me-2"></i> Balik ke List Orderan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if($order->status_pesanan === 'terkirim')
    @foreach($order->orderDetails as $detail)
    <!-- Modal Review Moved to Bottom for Fix -->
    <div class="modal fade" id="reviewModal{{ $detail->menu_id }}" tabindex="-1" aria-hidden="true" style="z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content brand-modal-content text-start">
                <div class="brand-modal-header d-flex justify-content-between align-items-center">
                    <h5 class="brand-modal-title mb-0">Kasih rating menu, yuk!</h5>
                    <button type="button" class="brand-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                </div>
                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <div class="brand-modal-body text-center">
                        <input type="hidden" name="menu_id" value="{{ $detail->menu_id }}">
                        <input type="hidden" name="target_menu_id" value="{{ $detail->menu_id }}">
                        
                        <div class="text-center mb-4">
                            <h6 class="fw-black text-muted text-uppercase tracking-wider mb-1">{{ $detail->menu->nama_display }}</h6>
                            <p class="small text-muted fw-bold">Pesanannya sudah sampai!</p>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold d-block mb-3 fs-5">Gimana rasanya? Puas gak?</label>
                            <select name="bintang" class="form-select brand-input text-center fs-5" required>
                                <option value="5">⭐⭐⭐⭐⭐ Enak banget!</option>
                                <option value="4">⭐⭐⭐⭐ Enak</option>
                                <option value="3" selected>⭐⭐⭐ Lumayan</option>
                                <option value="2">⭐⭐ Kureng ah</option>
                                <option value="1">⭐ Gak enak</option>
                            </select>
                        </div>

                        <div class="mb-0 text-start">
                            <label class="fw-bold mb-2">Tulis <em>review</em> jujurmu di sini</label>
                            <textarea name="isi_review" class="form-control brand-input" rows="4" maxlength="255" placeholder="Contoh: Bumbunya meresap bangeeet, porsinya juga pas!"></textarea>
                            <div class="form-text fw-bold text-muted small mt-2">Maksimal 255 karakter ya!</div>
                        </div>
                    </div>
                    <div class="p-4 pt-0">
                        <button type="submit" class="brand-btn brand-btn-primary w-100 py-3 fs-5">KIRIM REVIEW SEKARANG!</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endif

@endsection

