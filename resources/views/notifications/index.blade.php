@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Notifikasi Anda</h5>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($notifications as $notif)
                        <div class="list-group-item {{ $notif->is_read ? 'bg-light' : '' }}">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <h6 class="mb-1 {{ $notif->is_read ? 'text-muted' : 'fw-bold text-primary' }}">
                                    {{ $notif->title }}
                                </h6>
                                <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1 text-secondary">{{ $notif->message }}</p>
                            
                            @if(!$notif->is_read)
                                <form action="{{ route('notifications.read', $notif->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-link text-decoration-none p-0">
                                        Tandai dibaca
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="text-center p-5">
                            <img src="https://img.icons8.com/ios/100/cj-marketing/nothing-found.png" alt="Empty" style="opacity: 0.5; width: 64px;">
                            <p class="text-muted mt-3">Belum ada notifikasi baru.</p>
                        </div>
                    @endforelse
                </div>
                <div class="card-footer bg-white">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
