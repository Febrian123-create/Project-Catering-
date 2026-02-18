@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="brand-card brand-card-purple h-100">
                <div class="card-header bg-white border-bottom border-2 border-dark py-3">
                    <h4 class="mb-0 fw-bold text-uppercase letter-spacing-1">Edit Produk</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Nama Produk</label>
                            <input type="text" name="nama" class="form-control rounded-4 border-2 border-dark p-3 @error('nama') is-invalid @enderror" 
                                value="{{ old('nama', $product->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Kategori</label>
                            <select name="kategori" class="form-select rounded-4 border-2 border-dark p-3 @error('kategori') is-invalid @enderror">
                                <option value="">Pilih Kategori (Opsional)</option>
                                <option value="Sayur" {{ old('kategori', $product->kategori) == 'Sayur' ? 'selected' : '' }}>Sayur</option>
                                <option value="Daging" {{ old('kategori', $product->kategori) == 'Daging' ? 'selected' : '' }}>Daging</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Harga (Rp)</label>
                            <input type="number" name="harga" class="form-control rounded-4 border-2 border-dark p-3 @error('harga') is-invalid @enderror" 
                                value="{{ old('harga', $product->harga) }}" required min="0">
                            @error('harga')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control rounded-4 border-2 border-dark p-3 @error('deskripsi') is-invalid @enderror" 
                                rows="4">{{ old('deskripsi', $product->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold text-dark">Foto Produk (Opsional)</label>
                            @if($product->foto)
                                <div class="mb-3 d-flex align-items-center bg-light p-3 rounded-4 border border-2 border-dark">
                                    <img src="{{ asset('storage/' . $product->foto) }}" width="80" height="80" class="rounded-3 border border-dark me-3" style="object-fit: cover;">
                                    <div>
                                        <small class="d-block fw-bold text-uppercase opacity-50">Foto Saat Ini</small>
                                        <span class="text-muted small">Klik di bawah untuk mengganti foton</span>
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="foto" class="form-control rounded-4 border-2 border-dark p-3 @error('foto') is-invalid @enderror" accept="image/*">
                            <div class="form-text mt-2 ps-2">Biarkan kosong jika tidak ingin mengubah foto.</div>
                            @error('foto')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.products.index') }}" class="brand-btn brand-btn-warning text-decoration-none">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="brand-btn brand-btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Update Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
