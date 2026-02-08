@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="bi bi-cart3 me-2"></i>Keranjang Belanja</h2>

    @if($cartItems->count() > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Menu</th>
                                    <th>Tanggal</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->menu->product->nama }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ $item->menu->tgl_tersedia->format('d M') }}
                                            </span>
                                        </td>
                                        <td style="width: 100px;">
                                            <form action="{{ route('cart.update', $item->menu_id) }}" method="POST" class="d-flex">
                                                @csrf
                                                @method('PUT')
                                                <input type="number" name="qty" class="form-control form-control-sm text-center" 
                                                    value="{{ $item->qty }}" min="1"
                                                    onchange="this.form.submit()">
                                            </form>
                                        </td>
                                        <td class="text-end">
                                            {{ $item->menu->product->formatted_harga }}
                                        </td>
                                        <td class="text-end">
                                            <strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('cart.destroy', $item->menu_id) }}" method="POST" 
                                                onsubmit="return confirm('Hapus item ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('menus.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>Lanjut Belanja
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST" 
                        onsubmit="return confirm('Kosongkan keranjang?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-trash me-2"></i>Kosongkan
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 100px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Jumlah Item:</span>
                            <span>{{ $cartItems->sum('qty') }} porsi</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="h5 mb-0">Total:</span>
                            <span class="h5 mb-0 text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('orders.create') }}" class="btn btn-primary w-100 btn-lg">
                            <i class="bi bi-credit-card me-2"></i>Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <h4 class="mt-3 text-muted">Keranjang Kosong</h4>
            <p class="text-muted">Anda belum menambahkan menu apapun</p>
            <a href="{{ route('menus.index') }}" class="btn btn-primary">
                <i class="bi bi-menu-button me-2"></i>Lihat Menu
            </a>
        </div>
    @endif
</div>
@endsection
