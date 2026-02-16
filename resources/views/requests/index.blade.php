@extends('layouts.app')

@section('title', 'Riwayat Request Menu')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/requests.css') }}">
@endpush

@section('content')
<div class="container py-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <h2 class="fw-bold mb-1">Riwayat Request Menu</h2>
            <p class="text-muted mb-0">Lacak status permintaan menu kustom Anda.</p>
        </div>
        <a href="{{ route('requests.create') }}" class="btn btn-create-request text-white shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Buat Request Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        @forelse($requests as $req)
            <div class="col-md-6 col-lg-4">
                <div class="card request-item-card shadow-sm h-100 position-relative">
                    <div class="menu-accent accent-{{ $req->status }}"></div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="text-muted small mb-1">{{ $req->created_at->format('d M Y') }}</h6>
                                <h5 class="fw-bold text-dark mb-0 line-clamp-1">{{ $req->nama_menu }}</h5>
                            </div>
                            <span class="status-badge badge-{{ $req->status }}">
                                {{ $req->status == 'pending' ? 'Diproses' : ($req->status == 'accepted' ? 'Diterima' : 'Ditolak') }}
                            </span>
                        </div>

                        <div class="bg-light rounded-4 p-3 mb-3">
                            @if($req->deskripsi)
                                <div class="d-flex align-items-start mb-2">
                                    <i class="bi bi-card-text text-primary me-2 mt-1"></i>
                                    <span class="small line-clamp-2">{{ $req->deskripsi }}</span>
                                </div>
                            @endif
                            @if($req->asal_daerah)
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-geo-alt-fill text-primary me-2 mt-1"></i>
                                    <span class="small">{{ $req->asal_daerah }}</span>
                                </div>
                            @endif
                            @if(!$req->deskripsi && !$req->asal_daerah)
                                <span class="small text-muted fst-italic">Tidak ada detail tambahan.</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center">
                <div class="mb-4">
                    <i class="bi bi-journal-x display-1 text-muted opacity-25"></i>
                </div>
                <h4 class="fw-bold text-dark">Belum Ada Request</h4>
                <p class="text-muted mx-auto" style="max-width: 400px;">
                    Anda belum pernah membuat permintaan menu kustom. Mulai buat sekarang dan kami akan mewujudkan menu impian Anda!
                </p>
                <a href="{{ route('requests.create') }}" class="btn btn-primary rounded-pill px-4 mt-2">
                    Buat Request Pertama
                </a>
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $requests->links() }}
    </div>
</div>
@endsection
