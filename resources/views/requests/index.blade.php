@extends('layouts.app')

@section('title', 'Riwayat Request Menu')

@push('styles')
<style>
    .request-item-card {
        border: none;
        border-radius: 20px;
        transition: all 0.3s ease;
        overflow: hidden;
        background: white;
    }
    .request-item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
    }
    .status-badge {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 50px;
        letter-spacing: 0.5px;
    }
    .badge-pending { background: #fff8e1; color: #f57c00; }
    .badge-accepted { background: #e8f5e9; color: #2e7d32; }
    .badge-rejected { background: #ffebee; color: #c62828; }
    
    .menu-accent {
        width: 4px;
        height: 100%;
        position: absolute;
        left: 0;
        top: 0;
    }
    .accent-pending { background: #ffa000; }
    .accent-accepted { background: #4caf50; }
    .accent-rejected { background: #f44336; }

    .btn-create-request {
        background: var(--fh-blue);
        border: none;
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 700;
        transition: all 0.3s ease;
    }
    .btn-create-request:hover {
        background: #3e9ae8;
        transform: scale(1.05);
    }
</style>
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

    <div class="row g-4">
        @forelse($requests as $req)
            <div class="col-md-6 col-lg-4">
                <div class="card request-item-card shadow-sm h-100 position-relative">
                    <div class="menu-accent accent-{{ $req->status }}"></div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="text-muted small mb-1">{{ $req->created_at->format('d M Y') }}</h6>
                                <h5 class="fw-bold text-dark mb-0 line-clamp-1">{{ $req->subject }}</h5>
                            </div>
                            <span class="status-badge badge-{{ $req->status }}">
                                {{ $req->status == 'pending' ? 'Diproses' : ($req->status == 'accepted' ? 'Diterima' : 'Ditolak') }}
                            </span>
                        </div>

                        <div class="bg-light rounded-4 p-3 mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-egg-fried text-primary me-2"></i>
                                <span class="fw-bold small">{{ $req->nama_menu }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-people-fill text-primary me-2"></i>
                                <span class="small">{{ $req->jumlah_porsi }} Porsi</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-calendar-check-fill text-primary me-2"></i>
                                <span class="small">{{ \Carbon\Carbon::parse($req->tanggal_kebutuhan)->format('d M Y') }}</span>
                            </div>
                        </div>

                        @if($req->message)
                            <p class="text-muted small mb-0 line-clamp-2">
                                <i class="bi bi-chat-left-dots me-1"></i> "{{ $req->message }}"
                            </p>
                        @endif
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
