@extends('layouts.app')

@section('title', 'Request Menu Baru')

@push('styles')
<style>
    .request-card {
        border: none;
        border-radius: 24px;
        background: white;
    }
    .form-label {
        font-weight: 700;
        color: var(--text-black);
        margin-bottom: 8px;
    }
    .form-control {
        border-radius: 12px;
        border: 2px solid #eee;
        padding: 12px 18px;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: var(--fh-blue);
        box-shadow: none;
    }
    .input-group-text {
        background: #f8f9fa;
        border: 2px solid #eee;
        border-right: none;
        border-radius: 12px 0 0 12px;
        color: var(--fh-blue);
    }
    .form-control-with-icon {
        border-left: none;
        border-radius: 0 12px 12px 0;
    }
    .btn-send {
        background: var(--fh-blue);
        border: none;
        border-radius: 15px;
        padding: 15px;
        font-weight: 800;
        transition: all 0.3s ease;
    }
    .btn-send:hover {
        background: #3e9ae8;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(52, 152, 219, 0.2);
    }
</style>
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
                            <label class="form-label">Subjek Request</label>
                            <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" 
                                placeholder="Contoh: Menu Diet Rendah Kalori" value="{{ old('subject') }}" required>
                            @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Nama Menu</label>
                                <input type="text" name="nama_menu" class="form-control @error('nama_menu') is-invalid @enderror" 
                                    placeholder="Nama menu yang diinginkan" value="{{ old('nama_menu') }}" required>
                                @error('nama_menu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jumlah Porsi</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-people"></i></span>
                                    <input type="number" name="jumlah_porsi" class="form-control form-control-with-icon @error('jumlah_porsi') is-invalid @enderror" 
                                        placeholder="Min. 1" value="{{ old('jumlah_porsi') }}" required>
                                </div>
                                @error('jumlah_porsi') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Tanggal Kebutuhan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                <input type="date" name="tanggal_kebutuhan" class="form-control form-control-with-icon @error('tanggal_kebutuhan') is-invalid @enderror" 
                                    value="{{ old('tanggal_kebutuhan') }}" required>
                            </div>
                            <small class="text-muted mt-2 d-block">Pilih tanggal di masa mendatang agar kami punya waktu persiapan.</small>
                            @error('tanggal_kebutuhan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Pesan Tambahan (Opsional)</label>
                            <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="4" 
                                placeholder="Detail tambahan: alergi, tingkat kepedasan, dll.">{{ old('message') }}</textarea>
                            @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
