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
                    <form action="{{ route('admin.menus.update', $menu) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Tanggal Tersedia</label>
                            <input type="date" name="tgl_tersedia" class="form-control rounded-4 border-2 border-dark p-3 @error('tgl_tersedia') is-invalid @enderror" 
                                value="{{ old('tgl_tersedia', $menu->tgl_tersedia->format('Y-m-d')) }}" required>
                            @error('tgl_tersedia')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold text-dark">Pilih Produk</label>
                            <select name="product_id" class="form-select rounded-4 border-2 border-dark p-3 @error('product_id') is-invalid @enderror" required>
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
@endsection
