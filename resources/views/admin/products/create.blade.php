@extends('layouts.app')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="brand-card brand-card-blue h-100">
                <div class="card-header bg-white border-bottom border-2 border-dark py-3">
                    <h4 class="mb-0 fw-bold text-uppercase letter-spacing-1">Tambah Produk</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Nama Produk</label>
                            <input type="text" name="nama" class="form-control rounded-4 border-2 border-dark p-3 @error('nama') is-invalid @enderror" 
                                value="{{ old('nama') }}" required placeholder="Contoh: Nasi Goreng Spesial">
                            @error('nama')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Kategori</label>
                            <select name="kategori" class="form-select rounded-4 border-2 border-dark p-3 @error('kategori') is-invalid @enderror">
                                <option value="">Pilih Kategori (Opsional)</option>
                                <option value="Sayur" {{ old('kategori') == 'Sayur' ? 'selected' : '' }}>Sayur</option>
                                <option value="Daging" {{ old('kategori') == 'Daging' ? 'selected' : '' }}>Daging</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Harga (Rp)</label>
                            <input type="number" name="harga" class="form-control rounded-4 border-2 border-dark p-3 @error('harga') is-invalid @enderror" 
                                value="{{ old('harga') }}" required min="0" placeholder="Contoh: 25000">
                            @error('harga')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control rounded-4 border-2 border-dark p-3 @error('deskripsi') is-invalid @enderror" 
                                rows="4" placeholder="Deskripsi lengkap produk...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold text-dark">Foto Produk (Opsional)</label>
                            <input type="file" name="foto" class="form-control rounded-4 border-2 border-dark p-3 @error('foto') is-invalid @enderror" accept="image/*">
                            @error('foto')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.products.index') }}" class="brand-btn brand-btn-warning text-decoration-none">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="brand-btn brand-btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Simpan Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
