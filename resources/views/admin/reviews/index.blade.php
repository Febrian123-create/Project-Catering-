@extends('layouts.app')

@section('title', 'Kelola Ulasan - Dashboard Admin')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-black text-dark mb-0" style="letter-spacing: -1px;">KELOLA <span class="text-danger">ULASAN</span></h1>
            <p class="text-muted fw-bold mb-0">Moderasi ulasan pelanggan Dosinyam.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="brand-btn bg-white text-dark py-2 px-4">
            <i class="bi bi-arrow-left me-2"></i>Dashboard
        </a>
    </div>

    <div class="brand-card bg-white shadow-lg overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light border-bottom border-2 border-dark">
                    <tr>
                        <th class="px-4 py-3 fw-black text-uppercase small">ID</th>
                        <th class="px-4 py-3 fw-black text-uppercase small">Pelanggan</th>
                        <th class="px-4 py-3 fw-black text-uppercase small">Menu</th>
                        <th class="px-4 py-3 fw-black text-uppercase small text-center">Rating</th>
                        <th class="px-4 py-3 fw-black text-uppercase small">Komentar</th>
                        <th class="px-4 py-3 fw-black text-uppercase small">Tanggal</th>
                        <th class="px-4 py-3 fw-black text-uppercase small text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr class="border-bottom border-1 border-dark border-opacity-10">
                            <td class="px-4 py-4 fw-bold">#{{ $review->review_id }}</td>
                            <td class="px-4 py-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-purple text-dark border border-dark rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 30px; height: 30px; background: var(--fh-purple); font-size: 10px;">
                                        {{ substr($review->user->nama ?? 'P', 0, 1) }}
                                    </div>
                                    <span class="fw-bold text-dark">{{ $review->user->nama ?? 'Pengguna' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="badge bg-light text-dark border border-dark rounded-pill px-2 py-1 fw-bold small">
                                    {{ $review->menu->nama_display }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="text-warning fw-bold">
                                    {{ $review->bintang }} <i class="bi bi-star-fill"></i>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-muted small" style="max-width: 250px;">
                                <div class="italic">"{{ Str::limit($review->isi_review ?? '-', 100) }}"</div>
                            </td>
                            <td class="px-4 py-4 text-muted small fw-bold">
                                {{ $review->tgl_review->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill border-2 fw-bold px-3">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted opacity-50 mb-2"><i class="bi bi-chat-square-dots display-4"></i></div>
                                <p class="fw-bold text-muted mb-0">Belum ada ulasan yang masuk.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $reviews->links() }}
    </div>
</div>
@endsection
