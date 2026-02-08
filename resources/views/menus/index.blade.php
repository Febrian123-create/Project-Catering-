@extends('layouts.app')

@section('title', 'Menu')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Menu Tersedia</h2>
            <p class="text-muted mb-0">Pilih hidangan untuk tanggal yang diinginkan</p>
        </div>
        <form class="d-flex gap-2" method="GET">
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            <button type="submit" class="btn btn-primary">Filter</button>
            @if(request('date'))
                <a href="{{ route('menus.index') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </form>
    </div>

    <div class="row g-4">
        @forelse($menus as $menu)
            <div class="col-md-4">
                <div class="card h-100">
                    @if($menu->product->foto)
                        <img src="{{ asset('storage/' . $menu->product->foto) }}" class="card-img-top" 
                            alt="{{ $menu->product->nama }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                            style="height: 200px;">
                            <i class="bi bi-image display-4"></i>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $menu->tgl_tersedia->format('d M Y') }}
                            </span>
                        </div>
                        
                        <h5 class="card-title">{{ $menu->product->nama }}</h5>
                        <p class="text-muted small">{{ Str::limit($menu->product->deskripsi, 100) }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <small class="text-muted">Harga</small>
                                <div class="h5 text-primary mb-0">
                                    {{ $menu->product->formatted_harga }}
                                </div>
                            </div>
                            <a href="{{ route('menus.show', $menu->menu_id) }}" class="btn btn-primary">
                                <i class="bi bi-cart-plus me-1"></i> Pesan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x display-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">Tidak ada menu tersedia</h4>
                    <p class="text-muted">Silakan coba tanggal lain</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $menus->links() }}
    </div>
</div>
@endsection
