@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_id)

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Pesanan</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pesanan #{{ $order->order_id }}</h5>
                    {!! $order->status_badge !!}
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <small class="text-muted">Tanggal Pesan</small>
                            <div>{{ $order->tgl_pesan->format('d F Y') }}</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <small class="text-muted">Alamat Pengiriman</small>
                        <div>{{ $order->alamat_pengiriman }}</div>
                    </div>

                    @if($order->notes)
                        <div class="mb-4">
                            <small class="text-muted">Catatan</small>
                            <div>{{ $order->notes }}</div>
                        </div>
                    @endif

                    <hr>

                    <h6 class="mb-3">Detail Item & Review</h6>
                    <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Menu</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Subtotal</th>
                                <th>Review</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderDetails as $detail)
                                <tr>
                                    <td>
                                        <strong>{{ $detail->menu->product->nama }}</strong>
                                        <div class="small text-muted">{{ $detail->menu->tgl_tersedia->format('d M') }}</div>
                                    </td>
                                    <td class="text-center">{{ $detail->qty }}</td>
                                    <td class="text-center">{!! $detail->status_badge !!}</td>
                                    <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    <td>
                                        <!-- Check if user already reviewed this menu? -->
                                        <!-- For now, just show button to review -->
                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#reviewModal{{ $detail->menu_id }}">
                                            <i class="bi bi-star"></i> Review
                                        </button>

                                        <!-- Modal Review -->
                                        <div class="modal fade" id="reviewModal{{ $detail->menu_id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Review {{ $detail->menu->product->nama }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('reviews.store') }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <input type="hidden" name="menu_id" value="{{ $detail->menu_id }}">
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label">Rating</label>
                                                                <select name="bintang" class="form-select">
                                                                    <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                                                                    <option value="4">⭐⭐⭐⭐ (Puas)</option>
                                                                    <option value="3">⭐⭐⭐ (Cukup)</option>
                                                                    <option value="2">⭐⭐ (Kurang)</option>
                                                                    <option value="1">⭐ (Sangat Kurang)</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Ulasan</label>
                                                                <textarea name="isi_review" class="form-control" rows="3" 
                                                                    placeholder="Bagaimana rasa makanannya?"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Kirim Review</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-primary float-end">
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td class="text-end"><strong>{{ $order->formatted_total }}</strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-primary w-100">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
