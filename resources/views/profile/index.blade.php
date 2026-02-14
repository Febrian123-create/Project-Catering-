@extends('layouts.app')

@section('title', 'My Profile')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="profile-container">
    <div class="d-flex gap-5 justify-content-center align-items-stretch w-100" style="max-width: 1800px;">
        <!-- Sidebar -->
        <aside class="sidebar-pill">
            <a href="{{ route('profile.index') }}" class="sidebar-icon active" title="Profile">
                <i class="bi bi-person-fill"></i>
            </a>
            <a href="{{ route('notifications.index') }}" class="sidebar-icon" title="Notifications">
                <i class="bi bi-bell-fill"></i>
            </a>
            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                @csrf
                <button type="submit" class="sidebar-icon logout" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </aside>

        <!-- Main Content -->
        <div class="profile-card flex-grow-1">
            <h2 class="text-center mb-5 profile-title">My Profile</h2>

            <div class="row">
                <!-- User Info Form -->
                <div class="col-md-7">
                    <form>
                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold">Username</label>
                            <input type="text" class="form-control custom-input" value="{{ $user->username ?? '-' }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold">Name</label>
                            <input type="text" class="form-control custom-input" value="{{ $user->nama ?? $user->name ?? '-' }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold">Phone number</label>
                            <input type="text" class="form-control custom-input" value="{{ $user->kontak ?? '-' }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold">Address</label>
                            <textarea class="form-control custom-input" rows="3" readonly>{{ $user->alamat_default ?? '-' }}</textarea>
                        </div>
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="flex-grow-1">
                                <label class="form-label text-dark fw-bold">Password</label>
                                <input type="password" class="form-control custom-input" value="********" readonly>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('profile.password') }}" class="btn btn-coral-outline btn-sm rounded-pill px-3">change password</a>
                            </div>
                        </div>

                        <div class="text-center mt-5">
                            <a href="{{ route('profile.edit') }}" class="btn btn-coral btn-lg rounded-pill px-5">Edit Profile</a>
                        </div>
                    </form>
                </div>

                <!-- Profile Picture -->
                <div class="col-md-5 d-flex flex-column align-items-center mt-5">
                    <div class="profile-image-container mb-3">
                        @if($user->foto)
                            <img src="{{ asset('storage/' . $user->foto) }}" alt="Profile" class="profile-img">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama ?? 'User') }}&background=0D8ABC&color=fff" alt="Profile" class="profile-img">
                        @endif
                    </div>
                    
                    <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data" id="photoForm">
                        @csrf
                        <input type="file" name="profile_img" id="profile_img" class="d-none" accept="image/*" onchange="document.getElementById('photoForm').submit()">
                        <button type="button" class="btn btn-coral rounded-pill px-4" onclick="document.getElementById('profile_img').click()">
                            Change Image
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
