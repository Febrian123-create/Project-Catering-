@extends('layouts.app')

@section('title', 'Sign Up')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="signup-page-container register-page">
    <div class="signup-content-wrapper">
        <div class="signup-main-grid">
            <div class="form-section">
                <h1 class="signup-title">Sign Up</h1>
                
                @if ($errors->any())
                    <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.process') }}" method="POST">
                    @csrf
                    
                    <label class="form-label">Name</label>
                    <input type="text" name="nama" class="form-control" placeholder="Full Name" value="{{ old('nama') }}" required>

                    <div class="input-row">
                        <div class="input-group-custom">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Create your username" value="{{ old('username') }}" required>
                        </div>
                        <div class="input-group-custom">
                            <label class="form-label">Contact</label>
                            <input type="text" name="kontak" class="form-control" placeholder="Phone number" value="{{ old('kontak') }}" required>
                        </div>
                    </div>

                    <label class="form-label">Address</label>
                    <textarea name="alamat_default" class="form-control" placeholder="Address" rows="3" required>{{ old('alamat_default') }}</textarea>

                    <div class="input-row">
                        <div class="input-group-custom">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="input-group-custom">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-signup">Sign Up & Send OTP <i class="bi bi-send-fill ms-2"></i></button>
                </form>

                <p class="footer-text">Already have an account? <a href="{{ route('login') }}"><strong>Sign In</strong></a></p>
            </div>

            <div class="illustration-section">
                <img src="{{ asset('img/bento-landscape.png') }}" onerror="this.src='https://illustrations.popsy.co/amber/box-of-food.svg'" alt="Bento Illustration">
            </div>
        </div>
    </div>
</div>
@endsection
