@extends('layouts.app')

@section('title', 'Ulasan Pelanggan')

@section('content')
<div class="sticker-container">
    <i class="bi bi-star-fill sticker sticker-1"></i>
    <i class="bi bi-chat-heart sticker sticker-2"></i>
    <i class="bi bi-hand-thumbs-up sticker sticker-3"></i>
</div>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="section-title">Apa Kata Mereka?</h1>
        <p class="lead fw-bold text-muted">Ulasan jujur dari pelanggan setia kami.</p>
    </div>

    @if($reviews->isEmpty())
        <div class="brand-card p-5 text-center bg-white">
            <i class="bi bi-chat-dots display-1 text-muted mb-4 d-block"></i>
            <h3 class="fw-black">Belum ada ulasan nih...</h3>
            <p class="text-muted fw-bold">Jadilah yang pertama memberikan ulasan setelah pesananmu sampai!</p>
            <a href="{{ route('menus.index') }}" class="brand-btn brand-btn-primary mt-3 d-inline-block text-decoration-none">Cari Menu Enak</a>
        </div>
    @else
        <div class="row g-4">
            @foreach($reviews as $review)
                <div class="col-md-4 col-sm-6">
                    <div class="brand-card h-100 p-4 hover-lift">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-yellow rounded-circle d-flex align-items-center justify-content-center border border-2 border-dark" style="width: 50px; height: 50px;">
                                    <i class="bi bi-person-fill fs-4 text-dark"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <h6 class="fw-bold mb-0 text-dark">{{ $review->user->name ?? 'Pelanggan' }}</h6>
                                <small class="text-muted fw-bold">{{ $review->tgl_review->format('d M Y') }}</small>
                            </div>
                        </div>
                        
                        <div class="mb-2 text-warning fs-5">
                            {!! $review->stars !!}
                        </div>

                        <p class="fw-bold text-dark italic mb-3">"{{ $review->isi_review ?? 'Gak ada komentar, tapi bintangnya bicara!' }}"</p>
                        
                        <div class="mt-auto pt-3 border-top border-2 border-dark border-opacity-10">
                            <small class="text-uppercase tracking-wider fw-black text-danger fs-6">{{ $review->menu->nama_display }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-5 brand-pagination">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection
