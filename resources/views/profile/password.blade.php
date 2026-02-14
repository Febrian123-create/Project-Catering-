@extends('layouts.app')

@section('content')
<div class="profile-container">
    <div class="d-flex gap-5 justify-content-center align-items-stretch w-100" style="max-width: 1800px;">
        <!-- Sidebar -->
        <aside class="sidebar-pill" style="min-height: 500px">
            <a href="{{ route('profile.index') }}" class="sidebar-icon" title="Profile">
                <i class="bi bi-person-fill"></i>
            </a>
            <a href="{{ route('profile.password') }}" class="sidebar-icon active" title="Change Password">
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
            <h2 class="profile-title">Change Password</h2>

            @if ($errors->any())
                <div class="alert alert-danger rounded-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">Current Password</label>
                    <input type="password" name="current_password" class="form-control rounded-pill p-3" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">New Password</label>
                    <input type="password" name="password" class="form-control rounded-pill p-3" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control rounded-pill p-3" required>
                </div>

                <div class="d-flex gap-3 justify-content-end mt-4">
                    <a href="{{ route('profile.index') }}" class="btn btn-secondary rounded-pill px-4 py-2">Cancel</a>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 py-2">Update Password</button>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}?v={{ time() }}">
@endpush
