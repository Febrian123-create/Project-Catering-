@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="sticker-container">
    <i class="bi bi-cart-heart sticker sticker-1"></i>
    <i class="bi bi-stars sticker sticker-2"></i>
    <i class="bi bi-lightning sticker sticker-3"></i>
    <i class="bi bi-bag-heart sticker sticker-4"></i>
    <i class="bi bi-brightness-high sticker sticker-5"></i>
</div>

<div class="container py-5">
    <div class="d-flex align-items-center mb-5">
        <div class="bg-warning border border-2 border-dark p-3 rounded-circle me-3 shadow-sm">
            <i class="bi bi-cart3 h3 mb-0 text-dark"></i>
        </div>
        <div>
            <h2 class="section-title mb-1">Keranjang saya</h2>
            <p class="text-muted mb-0 fw-bold">Cek lagi pesanan kamu sebelum checkout!</p>
        </div>
    </div>

    @if($cartItems->count() > 0)
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="brand-card p-4">
                    <div class="d-none d-md-flex text-dark small fw-800 mb-4 px-2 border-bottom border-2 border-dark pb-3">
                        <div style="flex: 2;">MENU</div>
                        <div style="flex: 1;" class="text-center">TANGGAL</div>
                        <div style="flex: 1;" class="text-center">KUANTITAS</div>
                        <div style="flex: 1;" class="text-end">SUBTOTAL</div>
                        <div style="width: 50px;"></div>
                    </div>

                    @php
                        $groupedItems = $cartItems->groupBy(function($item) {
                            return $item->bundle_id ?: 'single_' . $item->menu_id;
                        });
                    @endphp

                    @foreach($groupedItems as $groupId => $items)
                        @php $firstItem = $items->first(); @endphp
                        @if($firstItem->bundle_id)
                            {{-- Bundle Display --}}
                            <div class="px-2 py-4 border-bottom border-1 border-dark border-opacity-10 bg-light-purple rounded-4 mb-3 mx-2 mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-3 px-3">
                                    <div>
                                        <span class="badge bg-purple text-white border border-dark px-3 py-1 rounded-pill fw-bold small mb-1">PAKET</span>
                                        <h5 class="fw-bold text-dark mb-0">{{ $firstItem->bundle_name }}</h5>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge rounded-pill bg-white text-dark border border-dark px-3 py-2 fw-bold">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            {{ $firstItem->menu->tgl_tersedia->format('d M') }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="px-3">
                                    @foreach($items as $item)
                                        <div class="d-flex justify-content-between align-items-center mb-2 small fw-bold text-muted">
                                            <div><i class="bi bi-check2-circle me-2 text-primary"></i>{{ $item->menu->nama_display }}</div>
                                            <div>{{ $item->qty }} porsi</div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-dark border-opacity-10 px-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <form action="{{ route('cart.update', $items[0]->menu_id) }}" method="POST" class="d-flex align-items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="bundle_id" value="{{ $firstItem->bundle_id }}">
                                            <input type="number" name="qty" class="form-control border-2 border-dark text-center fw-bold rounded-pill h-auto py-1" 
                                                value="{{ $items[0]->qty }}" min="1" style="max-width: 70px;"
                                                onchange="this.form.submit()">
                                            <small class="fw-bold">Porsi (Set)</small>
                                        </form>
                                    </div>
                                    <div class="d-flex align-items-center gap-4">
                                        <div class="text-end">
                                            <span class="fw-bold text-danger fs-5">Rp {{ number_format($items->sum('subtotal'), 0, ',', '.') }}</span>
                                        </div>
                                        <form action="{{ route('cart.destroy', $items[0]->menu_id) }}" method="POST" 
                                            onsubmit="return confirm('Hapus seluruh paket ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="bundle_id" value="{{ $firstItem->bundle_id }}">
                                            <button type="submit" class="btn btn-link text-danger p-0">
                                                <i class="bi bi-trash3-fill fs-5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Single Item Display --}}
                            @foreach($items as $item)
                                @if($item->menu)
                                    <div class="px-2 py-4 border-bottom border-1 border-dark border-opacity-10">
                                        <div class="row align-items-center g-3">
                                            <div class="col-12 col-md-5">
                                                <h6 class="fw-bold text-dark mb-1">{{ $item->menu->nama_display }}</h6>
                                                <p class="text-muted small fw-bold mb-0">{{ $item->menu->formatted_harga }} / porsi</p>
                                            </div>
                                            <div class="col-6 col-md-2 text-md-center">
                                                <span class="badge rounded-pill bg-light text-dark border border-dark px-3 py-2 fw-bold">
                                                    <i class="bi bi-calendar-event me-1"></i>
                                                    {{ $item->menu->tgl_tersedia->format('d M') }}
                                                </span>
                                            </div>
                                            <div class="col-6 col-md-2">
                                                <form action="{{ route('cart.update', $item->menu_id) }}" method="POST" class="d-flex justify-content-center">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" name="qty" class="form-control border-2 border-dark text-center fw-bold rounded-pill" 
                                                        value="{{ $item->qty }}" min="1" style="max-width: 80px;"
                                                        onchange="this.form.submit()">
                                                </form>
                                            </div>
                                            <div class="col-6 col-md-2 text-md-end">
                                                <span class="fw-bold text-danger fs-5">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="col-6 col-md-1 text-end">
                                                <form action="{{ route('cart.destroy', $item->menu_id) }}" method="POST" 
                                                    onsubmit="return confirm('Hapus item ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger p-0">
                                                        <i class="bi bi-trash3-fill fs-5"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    <div class="mt-4 pt-3 d-flex justify-content-between align-items-center">
                        <a href="{{ route('menus.index') }}" class="brand-btn brand-btn-warning text-decoration-none">
                            <i class="bi bi-arrow-left me-2"></i>Hunting Lagi
                        </a>
                        <form action="{{ route('cart.clear') }}" method="POST" 
                            onsubmit="return confirm('Kosongkan keranjang?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="brand-btn brand-btn-danger text-decoration-none text-white">
                                <i class="bi bi-trash me-2"></i>Buang Semua
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="brand-card brand-card-green p-4 sticky-top" style="top: 100px;">
                    <h4 class="fw-bold text-dark mb-4 border-bottom border-2 border-dark pb-3">Ringkasan Orderan</h4>
                    
                    <div class="d-flex justify-content-between mb-3 fw-bold text-dark">
                        <span>Total Porsi</span>
                        <span>{{ $cartItems->sum('qty') }} porsi</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-4 fw-bold text-dark">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <div class="border-top border-2 border-dark pt-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="h6 fw-bold text-dark mb-0">Total Tagihan</span>
                            <span class="h4 fw-bold text-danger mb-0">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('orders.create') }}" class="brand-btn brand-btn-primary text-white w-100 text-center text-decoration-none py-3 mb-3">
                        Sikat! <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    
                    <p class="small text-muted text-center fw-bold mb-0">
                        <i class="bi bi-shield-check-fill text-success me-1"></i> Pembayaran Aman & Terpercaya
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="brand-card py-5 px-4 text-center">
            <div class="mb-4">
                <i class="bi bi-cart-x display-1 text-muted opacity-25"></i>
            </div>
            <h3 class="fw-bold text-dark">Keranjang kamu masih kosong</h3>
            <p class="text-muted fw-bold mb-4">Kayanya kamu belum pilih menu buat hari ini. Pilih dulu, yuk, lapar nih!</p>
            <div>
                <a href="{{ route('menus.index') }}" class="brand-btn brand-btn-primary text-white text-decoration-none px-5 py-3">
                    <i class="bi bi-search me-2"></i>Cari menu untuk dipesan
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
