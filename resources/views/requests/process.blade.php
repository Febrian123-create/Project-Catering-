@extends('layouts.app')

@section('title', 'Finalisasi Request Menu')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('requests.index') }}" class="brand-btn bg-light text-dark border-dark me-3 text-decoration-none">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h2 class="fw-bold mb-0">Finalisasi Produk</h2>
                    <p class="text-muted mb-0 fw-bold">Ubah request menu menjadi produk yang bisa dipesan.</p>
                </div>
            </div>

            <div class="row g-4">
                <!-- Request Info -->
                <div class="col-md-4">
                    <div class="brand-card h-100">
                        <div class="card-body p-4">
                            <h6 class="text-muted small fw-bold text-uppercase mb-3">Detail Request</h6>
                            <h5 class="fw-bold text-dark mb-2">{{ $cateringRequest->nama_menu }}</h5>
                            <hr class="border-dark opacity-25">
                            
                            <p class="small mb-3">
                                <span class="d-block text-muted fw-bold">Deskripsi:</span>
                                <span class="fw-bold">{{ $cateringRequest->deskripsi ?: '-' }}</span>
                            </p>
                            
                            <p class="small mb-3">
                                <span class="d-block text-muted fw-bold">Asal Daerah:</span>
                                <span class="fw-bold text-danger">{{ $cateringRequest->asal_daerah ?: '-' }}</span>
                            </p>
                            
                            <p class="small mb-0">
                                <span class="d-block text-muted fw-bold">Oleh:</span>
                                <span class="fw-bold text-primary">{{ $cateringRequest->user->nama }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Finalize Form -->
                <div class="col-md-8">
                    <div class="brand-card brand-card-blue h-100 shadow-sm">
                        <div class="card-body p-4 p-md-5">
                            <form action="{{ route('admin.requests.finalize', $cateringRequest) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Harga Produk (Rp) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text border-2 border-dark bg-warning fw-bold text-dark">Rp</span>
                                        <input type="number" name="harga" class="form-control border-2 border-dark @error('harga') is-invalid @enderror" 
                                            placeholder="Contoh: 25000" value="{{ old('harga') }}" required>
                                    </div>
                                    @error('harga') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                                    <select name="kategori" class="form-select border-2 border-dark @error('kategori') is-invalid @enderror" required>
                                        <option value="" disabled selected>Pilih Kategori</option>
                                        <option value="Sayur" {{ old('kategori') == 'Sayur' ? 'selected' : '' }}>Sayur</option>
                                        <option value="Daging" {{ old('kategori') == 'Daging' ? 'selected' : '' }}>Daging</option>
                                    </select>
                                    @error('kategori') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-5">
                                    <label class="form-label fw-bold">Foto Produk <span class="text-danger">*</span></label>
                                    <input type="file" name="foto" class="form-control border-2 border-dark @error('foto') is-invalid @enderror" accept="image/*" required>
                                    <div class="form-text fw-bold text-muted mt-2">Maksimal 2MB (JPEG, PNG, JPG).</div>
                                    @error('foto') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Tanggal Tersedia <span class="text-danger">*</span></label>
                                    <input type="date" name="tgl_tersedia" class="form-control border-2 border-dark @error('tgl_tersedia') is-invalid @enderror" 
                                        value="{{ old('tgl_tersedia', now()->toDateString()) }}" required>
                                    <div class="form-text fw-bold text-muted mt-2">Kapan menu ini mulai tersedia untuk dipesan.</div>
                                    @error('tgl_tersedia') <div class="text-danger small mt-1 fw-bold">{{ $message }}</div> @enderror
                                </div>

                                <button type="submit" class="brand-btn brand-btn-primary w-100 py-3 fs-5">
                                    <i class="bi bi-check-circle-fill me-2"></i>Terima & Buat Produk
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
