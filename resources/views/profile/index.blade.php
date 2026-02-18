@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="sticker-container">
    <i class="bi bi-person-heart sticker sticker-1"></i>
    <i class="bi bi-stars sticker sticker-2"></i>
    <i class="bi bi-shield-check sticker sticker-3"></i>
    <i class="bi bi-camera sticker sticker-4"></i>
    <i class="bi bi-brightness-high sticker sticker-5"></i>
</div>

<div class="container py-5">
    <div class="d-flex flex-column flex-lg-row gap-5 justify-content-center align-items-stretch w-100 mx-auto" style="max-width: 1200px;">
        <!-- Sidebar -->
        <aside class="brand-card shadow-sm d-flex flex-lg-column align-items-center justify-content-around justify-content-lg-start py-4 px-3 gap-lg-4" style="min-width: 80px; border-radius: 40px !important;">
            <a href="{{ route('profile.index') }}" class="brand-btn brand-btn-warning text-dark p-0 d-flex align-items-center justify-content-center rounded-circle {{ Request::is('profile') ? 'border-4' : '' }}" title="Profile" style="width: 50px; height: 50px;">
                <i class="bi bi-person-fill fs-4"></i>
            </a>
            <a href="{{ route('notifications.index') }}" class="brand-btn bg-light text-dark p-0 d-flex align-items-center justify-content-center rounded-circle border-2" title="Notifications" style="width: 50px; height: 50px;">
                <i class="bi bi-bell-fill fs-4"></i>
            </a>
            <form action="{{ route('logout') }}" method="POST" id="logout-form" class="mt-lg-auto">
                @csrf
                <button type="submit" class="brand-btn brand-btn-danger text-white p-0 d-flex align-items-center justify-content-center rounded-circle" title="Logout" style="width: 50px; height: 50px;">
                    <i class="bi bi-box-arrow-right fs-4"></i>
                </button>
            </form>
        </aside>

        <!-- Main Content -->
        <div class="brand-card brand-card-purple flex-grow-1 p-4 p-md-5" style="border-radius: 40px !important;">
            <h2 class="section-title text-center mb-5">Profil Saya</h2>

            <div class="row g-5">
                <!-- User Info Form -->
                <div class="col-md-7">
                    <form>
                        <div class="mb-4">
                            <label class="form-label text-dark fw-bold small text-uppercase">Username</label>
                            <input type="text" class="form-control rounded-pill border-2 border-dark px-4 fw-bold" value="{{ $user->username ?? '-' }}" readonly style="height: 50px; background-color: #f8f9fa;">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-dark fw-bold small text-uppercase">Nama Lengkap</label>
                            <input type="text" class="form-control rounded-pill border-2 border-dark px-4 fw-bold" value="{{ $user->nama ?? $user->name ?? '-' }}" readonly style="height: 50px; background-color: #f8f9fa;">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-dark fw-bold small text-uppercase">Nomor Telepon</label>
                            <input type="text" class="form-control rounded-pill border-2 border-dark px-4 fw-bold" value="{{ $user->kontak ?? '-' }}" readonly style="height: 50px; background-color: #f8f9fa;">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-dark fw-bold small text-uppercase">Alamat Pengiriman</label>
                            <textarea class="form-control rounded-4 border-2 border-dark px-4 py-3 fw-bold" rows="3" readonly style="background-color: #f8f9fa;">{{ $user->alamat_default ?? '-' }}</textarea>
                        </div>
                        <div class="d-flex align-items-center gap-3 mb-5">
                            <div class="flex-grow-1">
                                <label class="form-label text-dark fw-bold small text-uppercase">Password</label>
                                <input type="password" class="form-control rounded-pill border-2 border-dark px-4 fw-bold" value="********" readonly style="height: 50px; background-color: #f8f9fa;">
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('profile.password') }}" class="brand-btn brand-btn-warning text-decoration-none py-2 px-3 small">Ubah</a>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('profile.edit') }}" class="brand-btn brand-btn-primary text-white text-decoration-none px-5 py-3 fs-5">
                                <i class="bi bi-pencil-square me-2"></i>Edit Profil
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Profile Picture -->
                <div class="col-md-5 d-flex flex-column align-items-center justify-content-center">
                    <div class="brand-card shadow-sm mb-4 d-flex align-items-center justify-content-center overflow-hidden" style="width: 250px; height: 250px; border-radius: 50% !important; border-width: 4px !important;">
                        @if($user->foto)
                            <img src="{{ asset('storage/' . $user->foto) }}" alt="Profile" class="w-100 h-100 object-fit-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama ?? 'User') }}&background=fdcb46&color=121212&bold=true" alt="Profile" class="w-100 h-100">
                        @endif
                    </div>
                    
                    <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data" id="photoForm">
                        @csrf
                        <input type="file" name="profile_img" id="profile_img" class="d-none" accept="image/*" onchange="document.getElementById('photoForm').submit()">
                        <button type="button" class="brand-btn brand-btn-warning py-2 px-4 fw-bold" onclick="document.getElementById('profile_img').click()">
                            <i class="bi bi-camera-fill me-2"></i>Ganti Foto
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
