@extends('layouts.app')

@section('title', 'Edit Menu')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Edit Menu</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('seller.menus.update', $menu) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Tanggal Tersedia</label>
                            <input type="date" name="tgl_tersedia" class="form-control @error('tgl_tersedia') is-invalid @enderror" 
                                value="{{ old('tgl_tersedia', $menu->tgl_tersedia->format('Y-m-d')) }}" required>
                            @error('tgl_tersedia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Pilih Produk</label>
                            <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->product_id }}" {{ old('product_id', $menu->product_id) == $product->product_id ? 'selected' : '' }}>
                                        {{ $product->nama }} - {{ $product->formatted_harga }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('seller.menus.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Update Menu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
