@extends('layouts.app')

@section('title', 'Request Menu Baru')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/requests.css') }}">
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4 text-center">
                <i class="bi bi-magic h1 text-primary mb-3 d-block"></i>
                <h2 class="fw-bold">Request Menu Kustom</h2>
                <p class="text-muted">Punya menu impian? Beritahu kami dan kami akan menyajikannya untuk Anda.</p>
            </div>

            <div class="card request-card shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('requests.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label">Nama Menu <span class="text-danger">*</span></label>
                            <input type="text" name="nama_menu" class="form-control @error('nama_menu') is-invalid @enderror" 
                                placeholder="Contoh: Rendang Sapi, Nasi Goreng Seafood" value="{{ old('nama_menu') }}" required>
                            @error('nama_menu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3" 
                                placeholder="Jelaskan menu yang Anda inginkan, misalnya: Rendang sapi empuk dengan bumbu rempah khas Padang...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Asal (Kota/Negara)</label>
                            <input type="text" name="asal_daerah" class="form-control @error('asal_daerah') is-invalid @enderror" 
                                placeholder="Contoh: Padang, Jepang, Italia" value="{{ old('asal_daerah') }}">
                            @error('asal_daerah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-grid gap-3 d-md-flex justify-content-md-between align-items-center">
                            <a href="{{ route('requests.index') }}" class="btn btn-link text-muted text-decoration-none small">
                                <i class="bi bi-arrow-left me-1"></i> Batal & Kembali
                            </a>
                            <button type="submit" class="btn btn-send text-white px-5 rounded-pill shadow-sm">
                                Kirim Request Menu <i class="bi bi-send-fill ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
