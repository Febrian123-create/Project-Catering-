@extends('layouts.app')

@section('title', 'Ulasan Pelanggan - Dosinyam')

@section('content')
<div class="sticker-container">
    <i class="bi bi-chat-heart sticker sticker-1"></i>
    <i class="bi bi-star sticker sticker-2"></i>
    <i class="bi bi-hand-thumbs-up sticker sticker-3"></i>
    <i class="bi bi-emoji-smile sticker sticker-4"></i>
    <i class="bi bi-bookmark-star sticker sticker-5"></i>
</div>

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-5">
        <ol class="breadcrumb brand-breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-dark">Home</a></li>
            <li class="breadcrumb-item active fw-bold text-danger">Semua Ulasan</li>
        </ol>
    </nav>

    <div class="text-center mb-5">
        <h1 class="display-3 fw-black text-dark mb-2" style="letter-spacing: -2px;">APA KATA <span class="text-danger">MEREKA?</span></h1>
        <p class="lead fw-bold text-muted">Ulasan asli dari para penikmat Dosinyam di seluruh penjuru.</p>
    </div>

    @if($reviews->count() > 0)
        <div class="row g-4">
            @foreach($reviews as $review)
                <div class="col-md-6 col-lg-4">
                    <div class="brand-card bg-white p-4 h-100 hover-lift shadow-sm">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-purple text-dark border border-dark rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 45px; height: 45px; background: var(--fh-purple);">
                                    {{ substr($review->user->nama ?? 'P', 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $review->user->nama ?? 'Pengguna' }}</div>
                                    <div class="text-warning small">
                                        @for($i = 0; $i < 5; $i++)
                                            <i class="bi bi-star-fill {{ $i < $review->bintang ? '' : 'text-muted opacity-25' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted fw-bold">{{ $review->tgl_review->format('d M Y') }}</small>
                        </div>
                        
                        <div class="mb-3">
                            <a href="{{ route('menus.show', $review->menu_id) }}" class="text-decoration-none">
                                <span class="badge bg-light text-dark border border-dark rounded-pill px-3 py-2 fw-bold small hover-lift">
                                    <i class="bi bi-cup-hot me-1 text-danger"></i> {{ $review->menu->nama_display }}
                                </span>
                            </a>
                        </div>

                        <p class="mb-0 text-dark fw-medium italic text-muted">"{{ $review->isi_review ?? 'Tanpa komentar.' }}"</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $reviews->links() }}
        </div>
    @else
        <div class="text-center py-5 bg-light rounded-brand border border-dashed border-2 border-dark border-opacity-25 my-5">
            <i class="bi bi-chat-left-dots display-1 text-muted opacity-25 d-block mb-4"></i>
            <h3 class="fw-bold text-dark">Belum Ada Ulasan</h3>
            <p class="text-muted fw-bold">Jadilah yang pertama memberikan ulasan setelah memesan menu kami!</p>
            <a href="{{ route('menus.index') }}" class="brand-btn brand-btn-primary mt-3 px-5 py-3 fs-5">Lihat Menu</a>
        </div>
    @endif
</div>
@endsection
