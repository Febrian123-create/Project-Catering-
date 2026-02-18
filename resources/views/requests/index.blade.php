@extends('layouts.app')

@section('title', 'Riwayat Request Menu')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/requests.css') }}">
@endpush

@section('content')
<div class="sticker-container">
    <i class="bi bi-envelope-heart sticker sticker-1"></i>
    <i class="bi bi-balloon sticker sticker-2"></i>
    <i class="bi bi-magic sticker sticker-3"></i>
    <i class="bi bi-gift sticker sticker-4"></i>
    <i class="bi bi-brightness-high sticker sticker-5"></i>
</div>
<div class="container py-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <h2 class="section-title mb-1">{{ Auth::user()->isAdmin() ? 'Kelola Request Menu' : 'Riwayat Request Menu' }}</h2>
            <p class="text-muted mb-0 fw-bold">{{ Auth::user()->isAdmin() ? 'Tinjau dan terima permintaan menu kustom dari buyer.' : 'Lacak status permintaan menu kustom Anda.' }}</p>
        </div>
        @if(!Auth::user()->isAdmin())
            <a href="{{ route('requests.create') }}" class="brand-btn brand-btn-primary text-white text-decoration-none shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>Buat Request Baru
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-2 border-dark shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i><span class="fw-bold">{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        @forelse($requests as $req)
            <div class="col-md-6 col-lg-4">
                <div class="brand-card {{ $req->status == 'pending' ? 'brand-card-blue' : ($req->status == 'accepted' ? 'brand-card-green' : 'brand-card-purple') }} h-100 position-relative">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="text-muted small mb-1 fw-bold text-uppercase opacity-75">
                                    {{ $req->created_at->format('d M Y') }}
                                    @if(Auth::user()->isAdmin())
                                        • <span class="text-primary">{{ $req->user->nama }}</span>
                                    @endif
                                </h6>
                                <h5 class="fw-bold text-dark mb-0 line-clamp-1 fs-5">{{ $req->nama_menu }}</h5>
                            </div>
                            <span class="badge {{ $req->status == 'pending' ? 'bg-warning' : ($req->status == 'accepted' ? 'bg-success' : 'bg-danger') }} rounded-pill border border-dark px-3 py-2 fw-bold shadow-sm">
                                {{ $req->status == 'pending' ? 'Pending' : ($req->status == 'accepted' ? 'Diterima' : 'Ditolak') }}
                            </span>
                        </div>

                        <div class="bg-light rounded-4 border border-2 border-dark p-3 mb-3">
                            @if($req->deskripsi)
                                <div class="d-flex align-items-start mb-2">
                                    <i class="bi bi-card-text text-primary me-2 mt-1"></i>
                                    <span class="small fw-bold">{{ Str::limit($req->deskripsi, 80) }}</span>
                                </div>
                            @endif
                            @if($req->asal_daerah)
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-geo-alt-fill text-danger me-2 mt-1"></i>
                                    <span class="small fw-bold">{{ $req->asal_daerah }}</span>
                                </div>
                            @endif
                        </div>

                        @if(Auth::user()->isAdmin() && $req->status == 'pending')
                            <form action="{{ route('admin.requests.accept', $req) }}" method="POST" class="mt-auto">
                                @csrf
                                <button type="submit" class="brand-btn brand-btn-primary w-100">
                                    <i class="bi bi-check-lg me-2"></i>Accept Request
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center">
                <div class="mb-4">
                    <i class="bi bi-journal-x display-1 text-muted opacity-25"></i>
                </div>
                <h4 class="fw-bold text-dark fs-3">{{ Auth::user()->isAdmin() ? 'Tidak Ada Request Pending' : 'Belum Ada Request' }}</h4>
                <p class="text-muted mx-auto fw-bold" style="max-width: 400px;">
                    {{ Auth::user()->isAdmin() ? 'Semua permintaan menu dari buyer telah diproses.' : 'Anda belum pernah membuat permintaan menu kustom. Mulai buat sekarang!' }}
                </p>
                @if(!Auth::user()->isAdmin())
                    <a href="{{ route('requests.create') }}" class="brand-btn brand-btn-primary text-white text-decoration-none mt-3">
                        <i class="bi bi-stars me-2"></i>Buat Request Pertama
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $requests->links() }}
    </div>
</div>
@endsection
