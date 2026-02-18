@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="sticker-container">
    <i class="bi bi-bell sticker sticker-1"></i>
    <i class="bi bi-chat-dots sticker sticker-2"></i>
    <i class="bi bi-megaphone sticker sticker-3"></i>
    <i class="bi bi-envelope sticker sticker-4"></i>
    <i class="bi bi-brightness-high sticker sticker-5"></i>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="d-flex align-items-center mb-5">
                <div class="bg-warning border border-2 border-dark p-3 rounded-circle me-3 shadow-sm">
                    <i class="bi bi-bell h3 mb-0 text-dark"></i>
                </div>
                <div>
                    <h2 class="section-title mb-1">Notifikasi Anda</h2>
                    <p class="text-muted mb-0 fw-bold">Informasi terbaru mengenai pesanan dan akun Anda.</p>
                </div>
            </div>

            <div class="brand-card overflow-hidden">
                <div class="list-group list-group-flush">
                    @forelse($notifications as $notif)
                        <div class="list-group-item p-4 border-bottom border-1 border-dark border-opacity-10 {{ $notif->is_read ? 'bg-light bg-opacity-50' : '' }}">
                            <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                                <h5 class="mb-0 {{ $notif->is_read ? 'text-muted' : 'fw-bold text-dark' }}">
                                    @if(!$notif->is_read)
                                        <i class="bi bi-circle-fill text-danger small me-2" style="font-size: 0.6rem;"></i>
                                    @endif
                                    {{ $notif->title }}
                                </h5>
                                <small class="text-muted fw-bold">{{ $notif->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-3 text-secondary fw-bold">{{ $notif->message }}</p>
                            
                            @if(!$notif->is_read)
                                <form action="{{ route('notifications.read', $notif->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="brand-btn brand-btn-primary btn-sm py-1 px-3">
                                        Tandai dibaca
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="text-center p-5">
                            <div class="mb-4">
                                <i class="bi bi-bell-slash display-1 text-muted opacity-25"></i>
                            </div>
                            <h4 class="fw-bold text-dark">Belum Ada Notifikasi</h4>
                            <p class="text-muted fw-bold mt-2">Anda akan melihat pemberitahuan di sini saat ada kabar terbaru!</p>
                        </div>
                    @endforelse
                </div>
                @if($notifications->hasPages())
                    <div class="card-footer bg-white border-top border-2 border-dark p-4">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
