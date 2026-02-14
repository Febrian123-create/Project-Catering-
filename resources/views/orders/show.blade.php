@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_id)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/orders.css') }}">
@endpush

@section('content')
<div class="container py-5 text-black">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none">Pesanan</a></li>
            <li class="breadcrumb-item active">Detail #{{ $order->order_id }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Info Utama Pesanan -->
        <div class="col-lg-8">
            <div class="card order-header-card shadow-sm mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="text-muted small text-uppercase tracking-wider d-block">ORDER ID</span>
                        <h3 class="fw-bold mb-0">#{{ $order->order_id }}</h3>
                    </div>
                    {!! $order->status_badge !!}
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">Tanggal Pesanan</small>
                        <div class="fw-bold"><i class="bi bi-calendar-check me-2 text-primary"></i>{{ $order->tgl_pesan->format('d F Y') }}</div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">Status Pembayaran</small>
                        <div class="fw-bold">
                            @if($order->status_pembayaran == 'pending')
                                <span class="text-warning"><i class="bi bi-hourglass-split me-2"></i>Menunggu Pembayaran</span>
                            @elseif($order->status_pembayaran == 'paid')
                                <span class="text-success"><i class="bi bi-check-circle-fill me-2"></i>Lunas</span>
                            @else
                                <span class="text-danger"><i class="bi bi-x-circle-fill me-2"></i>Dibatalkan</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block mb-1">Alamat Pengiriman</small>
                        <div class="p-3 bg-light rounded-3">
                            <i class="bi bi-geo-alt me-2 text-primary"></i>{{ $order->alamat_pengiriman }}
                        </div>
                    </div>
                    @if($order->notes)
                    <div class="col-12">
                        <small class="text-muted d-block mb-1">Catatan</small>
                        <div class="p-3 bg-light rounded-3 italic">"{{ $order->notes }}"</div>
                    </div>
                    @endif
                </div>

                <hr class="my-4 opacity-10">
                
                <h5 class="fw-bold mb-4">Daftar Menu & Ulasan</h5>
                @foreach($order->orderDetails as $detail)
                    <div class="card item-card p-3">
                        <div class="row align-items-center g-3">
                            <div class="col-md-5">
                                <h6 class="fw-bold mb-1">{{ $detail->menu->product->nama }}</h6>
                                <p class="text-muted small mb-0">{{ $detail->menu->tgl_tersedia->format('d M Y') }}</p>
                            </div>
                            <div class="col-md-2 text-center">
                                <span class="badge rounded-pill bg-white text-dark border px-3 py-2">{{ $detail->qty }} Porsi</span>
                            </div>
                            <div class="col-md-3 text-end">
                                <span class="fw-bold text-primary">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="button" class="btn btn-sm btn-outline-warning rating-btn w-100" 
                                    data-bs-toggle="modal" data-bs-target="#reviewModal{{ $detail->menu_id }}">
                                    <i class="bi bi-star"></i> Review
                                </button>
                                
                                <!-- Modern Modal Review -->
                                <div class="modal fade" id="reviewModal{{ $detail->menu_id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0" style="border-radius: 20px;">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="fw-bold">Beri Rating Menu</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('reviews.store') }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <input type="hidden" name="menu_id" value="{{ $detail->menu_id }}">
                                                    <p class="text-muted">{{ $detail->menu->product->nama }}</p>
                                                    <div class="mb-4">
                                                        <label class="form-label d-block text-center mb-3">Seberapa enak menu ini?</label>
                                                        <div class="d-flex justify-content-center gap-2">
                                                            <select name="bintang" class="form-select w-75 rounded-pill text-center border-2">
                                                                <option value="5">⭐⭐⭐⭐⭐ Sangat Enak</option>
                                                                <option value="4">⭐⭐⭐⭐ Enak</option>
                                                                <option value="3">⭐⭐⭐ Lumayan</option>
                                                                <option value="2">⭐⭐ Kurang</option>
                                                                <option value="1">⭐ Buruk</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Tulis Ulasan Anda</label>
                                                        <textarea name="isi_review" class="form-control rounded-4" rows="3" placeholder="Ceritakan pengalaman rasa Anda..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">Kirim Ulasan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Sidebar Pembayaran -->
        <div class="col-lg-4">
            <div class="card payment-card shadow-sm sticky-top" style="top: 100px;">
                <h5 class="fw-bold mb-4">Informasi Pembayaran</h5>
                
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Tagihan:</span>
                        <h4 class="fw-bold text-primary mb-0">{{ $order->formatted_total }}</h4>
                    </div>
                </div>

                @if($order->status_pembayaran == 'pending')
                    <div class="alert alert-warning border-0 rounded-4 p-3 small mb-4">
                        <i class="bi bi-info-circle me-2"></i> Selesaikan pembayaran Anda secepatnya untuk memproses pesanan melalui Midtrans.
                    </div>
                    
                    <button id="pay-button" class="btn btn-pay w-100 shadow-sm mb-3">
                        <i class="bi bi-shield-lock me-2"></i> Bayar Sekarang
                    </button>

                    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
                    <script type="text/javascript">
                        document.getElementById('pay-button').onclick = function(){
                            const token = '{{ $snapToken }}';
                            if (!token || token.includes('DUMMY')) {
                                alert('Snap Token belum tersedia atau sedang dalam mode Testing.\nJika ini Sandbox, pastikan Server Key sudah benar.');
                                return;
                            }
                            
                            window.snap.pay(token, {
                                onSuccess: function(result){
                                    alert("Pembayaran berhasil!"); 
                                    window.location.reload();
                                },
                                onPending: function(result){
                                    alert("Menunggu pembayaran!"); 
                                    window.location.reload();
                                },
                                onError: function(result){
                                    alert("Pembayaran gagal!");
                                    console.log(result);
                                },
                                onClose: function(){
                                    alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                                }
                            });
                        };
                    </script>
                @else
                    <div class="text-center py-4 bg-white rounded-4 border-2 border-dashed border-success">
                        <i class="bi bi-patch-check-fill text-success display-4 mb-3"></i>
                        <h5 class="fw-bold text-success">Lunas & Terbayar</h5>
                        <p class="small text-muted mb-0">Terima kasih atas pesanan Anda!</p>
                    </div>
                @endif

                <div class="mt-4 pt-3 border-top opacity-10">
                    <a href="{{ route('orders.index') }}" class="btn btn-link text-muted text-decoration-none w-100">
                        <i class="bi bi-arrow-left me-2"></i> Kembali ke Riwayat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
