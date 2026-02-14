@extends('layouts.app')

@section('content')
<div class="profile-container">
    <div class="d-flex gap-5 justify-content-center align-items-stretch w-100" style="max-width: 1800px;">
        <!-- Sidebar -->
        <aside class="sidebar-pill" style="min-height: 500px">
            <a href="{{ route('profile.index') }}" class="sidebar-icon active" title="Profile">
                <i class="bi bi-person-fill"></i>
            </a>
            <a href="{{ route('profile.password') }}" class="sidebar-icon" title="Change Password">
                <i class="bi bi-lock-fill"></i>
            </a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="sidebar-icon" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </aside>

        <!-- Profile Card -->
        <section class="profile-card">
            <h2 class="profile-title">Edit Profile</h2>
            
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">Name</label>
                    <input type="text" name="nama" class="form-control rounded-pill p-3" value="{{ old('nama', $user->nama) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Username</label>
                    <input type="text" name="username" class="form-control rounded-pill p-3" value="{{ old('username', $user->username) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Phone Number</label>
                    <input type="text" name="kontak" class="form-control rounded-pill p-3" value="{{ old('kontak', $user->kontak) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Address</label>
                    <textarea name="alamat_default" class="form-control rounded-4 p-3" rows="3" required>{{ old('alamat_default', $user->alamat_default) }}</textarea>
                </div>

                <div class="d-flex gap-3 justify-content-end mt-4">
                    <a href="{{ route('profile.index') }}" class="btn btn-secondary rounded-pill px-4 py-2">Cancel</a>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 py-2">Save Changes</button>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}?v={{ time() }}">
@endpush
